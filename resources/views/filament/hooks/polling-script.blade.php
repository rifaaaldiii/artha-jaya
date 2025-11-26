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
                        },
                    });

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

