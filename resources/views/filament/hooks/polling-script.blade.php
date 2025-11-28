@php
    $pollingConfig = [
        'endpoint' => route('polling.triggers'),
        'interval' => (int) config('polling.interval_ms', 3000),
        'channels' => config('polling.channels', []),
        'events' => config('polling.events', []),
    ];
@endphp

<script>
    (() => {
        const config = @json($pollingConfig);

        class AJTriggeredPoller {
            constructor({ endpoint, interval, channels, events }) {
                this.endpoint = endpoint;
                this.interval = interval;
                this.channels = channels ?? [];
                this.events = events ?? {};
                this.timer = null;
                this.lastSnapshot = {};
                this.isRunning = false;
            }

            start() {
                if (this.isRunning || ! this.channels.length) {
                    return;
                }

                this.isRunning = true;
                this.tick();
                this.timer = setInterval(() => this.tick(), this.interval);
            }

            stop() {
                if (this.timer) {
                    clearInterval(this.timer);
                }

                this.isRunning = false;
            }

            async tick() {
                try {
                    const response = await fetch(this.endpoint + '?channels=' + encodeURIComponent(this.channels.join(',')), {
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    // Handle 403 errors dengan smart detection
                    if (response.status === 403) {
                        try {
                            const errorData = await response.json();
                            
                            // Session expired - Auto logout
                            if (errorData.logout === true || errorData.error === 'Session expired') {
                                console.warn('Polling: Session expired. Auto logout...');
                                this.handleAutoLogout(errorData.redirect || '/admin/login');
                                return;
                            }
                            
                            // CSRF expired but session valid - Retry with new token
                            if (errorData.retry === true && errorData.csrf_token) {
                                console.info('Polling: CSRF token expired. Updating token and retrying...');
                                this.updateCsrfToken(errorData.csrf_token);
                                
                                // Retry request setelah update token
                                setTimeout(() => this.tick(), 500);
                                return;
                            }
                        } catch (parseError) {
                            // Jika response bukan JSON, mungkin error lain
                            console.error('Polling: Error parsing 403 response', parseError);
                        }
                        return;
                    }

                    if (! response.ok) {
                        return;
                    }

                    const payload = await response.json();
                    const incoming = payload.channels ?? {};

                    Object.entries(incoming).forEach(([channel, version]) => {
                        if (! this.lastSnapshot[channel]) {
                            this.lastSnapshot[channel] = version;
                            return;
                        }

                        if (Number(version) !== Number(this.lastSnapshot[channel])) {
                            this.lastSnapshot[channel] = version;
                            this.notify(channel);
                        }
                    });
                } catch (error) {
                    console.error('AJTriggeredPoller error:', error);
                }
            }

            handleAutoLogout(redirectUrl) {
                // Stop polling
                this.stop();
                
                // Show notification jika Livewire tersedia
                if (window.Livewire?.dispatch) {
                    window.Livewire.dispatch('session-expired-warning');
                }
                
                // Redirect ke login setelah 1.5 detik (beri waktu untuk notification)
                setTimeout(() => {
                    window.location.href = redirectUrl;
                }, 1500);
            }

            updateCsrfToken(token) {
                // Update CSRF token di meta tag
                const meta = document.querySelector('meta[name="csrf-token"]');
                if (meta) {
                    meta.setAttribute('content', token);
                }
                
                // Update di semua form dengan name="_token"
                document.querySelectorAll('input[name="_token"]').forEach(input => {
                    input.value = token;
                });
                
                // Update Livewire CSRF token jika tersedia
                if (window.Livewire) {
                    window.Livewire.csrf = token;
                }
            }

            notify(channel) {
                const eventNames = this.events[channel] ?? [];

                eventNames.forEach((eventName) => {
                    if (window.Livewire?.dispatch) {
                        window.Livewire.dispatch(eventName);
                    } else {
                        document.addEventListener('livewire:init', () => {
                            window.Livewire?.dispatch?.(eventName);
                        }, { once: true });
                    }
                });
            }
        }

        if (! window.AJTriggeredPollerInstance) {
            window.AJTriggeredPollerInstance = new AJTriggeredPoller(config);

            const startPoller = () => window.AJTriggeredPollerInstance.start();

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', startPoller, { once: true });
            } else {
                startPoller();
            }
        }
    })();
</script>

