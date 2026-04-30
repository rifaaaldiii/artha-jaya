@props([
    'wireModel' => 'filters',
    'startDate' => null,
    'endDate' => null,
    'currentMonth' => null,
    'currentYear' => null,
])

<style>
    :root {
        --aj-cal-bg: #ffffff;
        --aj-cal-border: #e5e7eb;
        --aj-cal-text: #111827;
        --aj-cal-muted: #6b7280;
        --aj-cal-primary: #059669;
        --aj-cal-primary-light: #d1fae5;
        --aj-cal-primary-dark: #047857;
        --aj-cal-soft-bg: #f8fafc;
        --aj-cal-divider: #f3f4f6;
        --aj-cal-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .dark,
    [data-theme="dark"],
    .filament-theme-dark {
        --aj-cal-bg: #1e293b;
        --aj-cal-border: #334155;
        --aj-cal-text: #f8fafc;
        --aj-cal-muted: #94a3b8;
        --aj-cal-primary: #34d399;
        --aj-cal-primary-light: rgba(52, 211, 153, 0.15);
        --aj-cal-primary-dark: #6ee7b7;
        --aj-cal-soft-bg: #0f172a;
        --aj-cal-divider: #1e293b;
        --aj-cal-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    .aj-calendar-container {
        border-radius: 12px;
    }

    .aj-calendar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 2px solid var(--aj-cal-divider);
    }
    
    .aj-calendar-nav-btn {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--aj-cal-soft-bg);
        border: 2px solid var(--aj-cal-border);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        color: var(--aj-cal-primary);
    }

    .aj-calendar-nav-btn:hover {
        background: var(--aj-cal-primary-light);
        border-color: var(--aj-cal-primary);
        transform: scale(1.05);
    }

    .aj-calendar-nav-btn svg {
        width: 15px;
        height: 15px;
    }

    .aj-calendar-month-year {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .aj-calendar-select {
        padding: 6px 32px 6px 12px;
        border: 2px solid var(--aj-cal-border);
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        color: var(--aj-cal-text);
        background: var(--aj-cal-soft-bg);
        cursor: pointer;
        transition: all 0.2s ease;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 8px center;
        background-repeat: no-repeat;
        background-size: 16px;
    }

    .aj-calendar-select:hover {
        border-color: var(--aj-cal-primary);
        background-color: var(--aj-cal-primary-light);
    }

    .aj-calendar-select:focus {
        outline: none;
        border-color: var(--aj-cal-primary);
        box-shadow: 0 0 0 3px var(--aj-cal-primary-light);
    }

    .aj-calendar-weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--aj-cal-divider);
    }

    .aj-calendar-weekday {
        text-align: center;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--aj-cal-muted);
        padding: 8px 0;
    }

    .aj-calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
    }

    .aj-calendar-day {
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        color: var(--aj-cal-text);
        cursor: pointer;
        transition: all 0.2s ease;
        border: 2px solid transparent;
        position: relative;
    }

    .aj-calendar-day:hover:not(.disabled):not(.selected):not(.in-range) {
        background: var(--aj-cal-primary-light);
        color: var(--aj-cal-primary);
        border-color: var(--aj-cal-primary);
        transform: scale(1.05);
    }

    .aj-calendar-day.today {
        border-color: var(--aj-cal-primary);
        background: var(--aj-cal-primary-light);
        color: var(--aj-cal-primary);
        font-weight: 700;
    }

    .aj-calendar-day.selected {
        background: var(--aj-cal-primary);
        color: white;
        border-color: var(--aj-cal-primary);
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.4);
    }

    .aj-calendar-day.in-range {
        background: var(--aj-cal-primary-light);
        color: var(--aj-cal-primary);
        border-color: var(--aj-cal-primary-light);
        border-radius: 10px;
    }

    .aj-calendar-day.other-month {
        color: var(--aj-cal-muted);
        opacity: 0.4;
        cursor: not-allowed;
    }

    .aj-calendar-day.disabled {
        color: var(--aj-cal-muted);
        opacity: 0.3;
        cursor: not-allowed;
    }

    .aj-calendar-day.start-range {
        border-radius: 10px 0 0 10px;
    }

    .aj-calendar-day.end-range {
        border-radius: 0 10px 10px 0;
    }

    .aj-calendar-selected-info {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 2px solid var(--aj-cal-divider);
        text-align: center;
        font-size: 13px;
        color: var(--aj-cal-muted);
    }

    .aj-calendar-selected-info.has-selection {
        color: var(--aj-cal-primary);
        font-weight: 600;
    }

    .aj-calendar-clear-btn {
        margin-top: 12px;
        width: 100%;
        padding: 8px;
        background: var(--aj-cal-soft-bg);
        border: 2px solid var(--aj-cal-border);
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: var(--aj-cal-muted);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .aj-calendar-clear-btn:hover {
        background: var(--aj-cal-border);
        color: var(--aj-cal-text);
    }

    @media (max-width: 768px) {
        .aj-calendar-container {
            padding: 16px;
        }

        .aj-calendar-day {
            height: 36px;
            font-size: 13px;
        }

        .aj-calendar-select {
            font-size: 13px;
            padding: 5px 28px 5px 10px;
        }
    }
</style>

<div class="aj-calendar-container" x-data="dateRangePicker(@js($startDate), @js($endDate), @js($currentMonth ?? 1), @js($currentYear ?? date('Y')))" x-init="init()">
    <div class="aj-calendar-header">
        <button type="button" @click="previousMonth()" class="aj-calendar-nav-btn" aria-label="Previous month">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        
        <div class="aj-calendar-month-year">
            <select x-model="month" @change="updateCalendar()" class="aj-calendar-select" aria-label="Select month">
                <template x-for="(monthName, index) in monthNames" :key="index">
                    <option :value="index + 1" x-text="monthName"></option>
                </template>
            </select>
            
            <select x-model="year" @change="updateCalendar()" class="aj-calendar-select" aria-label="Select year">
                <template x-for="y in yearRange" :key="y">
                    <option :value="y" x-text="y"></option>
                </template>
            </select>
        </div>
        
        <button type="button" @click="nextMonth()" class="aj-calendar-nav-btn" aria-label="Next month">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>

    <div class="aj-calendar-weekdays">
        <template x-for="day in dayNames" :key="day">
            <div class="aj-calendar-weekday" x-text="day"></div>
        </template>
    </div>

    <div class="aj-calendar-days">
        <template x-for="(day, index) in calendarDays" :key="index">
            <button
                type="button"
                @click="selectDate(day)"
                class="aj-calendar-day"
                :class="{
                    'other-month': !day.currentMonth,
                    'today': day.isToday,
                    'selected': day.isSelected,
                    'in-range': day.isInRange,
                    'start-range': day.isStartRange,
                    'end-range': day.isEndRange,
                    'disabled': !day.currentMonth
                }"
                x-text="day.day"
                :disabled="!day.currentMonth"
            ></button>
        </template>
    </div>

    <div class="aj-calendar-selected-info" :class="{ 'has-selection': startDate && endDate }">
        <template x-if="!startDate && !endDate">
            <span>Pilih rentang tanggal</span>
        </template>
        <template x-if="startDate && !endDate">
            <span x-text="'Tanggal awal: ' + formatDate(startDate) + ' (klik tanggal akhir)'"></span>
        </template>
        <template x-if="startDate && endDate">
            <span x-text="formatDate(startDate) + ' - ' + formatDate(endDate)"></span>
        </template>
    </div>

    <button type="button" @click="clearSelection()" class="aj-calendar-clear-btn" x-show="startDate || endDate">
        Hapus Pilihan
    </button>
</div>

<script>
    function dateRangePicker(initialStartDate, initialEndDate, initialMonth, initialYear) {
        return {
            month: initialMonth ?? 1, // Default to January
            year: initialYear ?? new Date().getFullYear(), // Default to current year
            startDate: initialStartDate,
            endDate: initialEndDate,
            tempStartDate: null,
            
            monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            dayNames: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            
            get yearRange() {
                const years = [];
                for (let i = 2000; i <= 2050; i++) {
                    years.push(i);
                }
                return years;
            },
            
            get calendarDays() {
                const days = [];
                const firstDay = new Date(this.year, this.month - 1, 1);
                const lastDay = new Date(this.year, this.month, 0);
                const startDayOfWeek = firstDay.getDay();
                const daysInMonth = lastDay.getDate();
                
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                // Previous month days
                const prevMonthLastDay = new Date(this.year, this.month - 1, 0).getDate();
                for (let i = startDayOfWeek - 1; i >= 0; i--) {
                    const day = prevMonthLastDay - i;
                    const date = new Date(this.year, this.month - 2, day);
                    days.push({
                        day: day,
                        date: date,
                        currentMonth: false,
                        isToday: false,
                        isSelected: false,
                        isInRange: false,
                        isStartRange: false,
                        isEndRange: false
                    });
                }
                
                // Current month days
                for (let i = 1; i <= daysInMonth; i++) {
                    const date = new Date(this.year, this.month - 1, i);
                    const dateStr = this.formatDateISO(date);
                    
                    const isToday = date.getTime() === today.getTime();
                    const isSelected = dateStr === this.startDate || dateStr === this.endDate;
                    const isStartRange = dateStr === this.startDate;
                    const isEndRange = dateStr === this.endDate;
                    
                    let isInRange = false;
                    if (this.startDate && this.endDate) {
                        const start = new Date(this.startDate);
                        const end = new Date(this.endDate);
                        isInRange = date > start && date < end;
                    }
                    
                    days.push({
                        day: i,
                        date: date,
                        currentMonth: true,
                        isToday: isToday,
                        isSelected: isSelected,
                        isInRange: isInRange,
                        isStartRange: isStartRange,
                        isEndRange: isEndRange
                    });
                }
                
                // Next month days
                const remainingDays = 42 - days.length;
                for (let i = 1; i <= remainingDays; i++) {
                    const date = new Date(this.year, this.month, i);
                    days.push({
                        day: i,
                        date: date,
                        currentMonth: false,
                        isToday: false,
                        isSelected: false,
                        isInRange: false,
                        isStartRange: false,
                        isEndRange: false
                    });
                }
                
                return days;
            },
            
            init() {
                this.updateCalendar();
            },
            
            updateCalendar() {
                // Force re-computation of calendarDays
                this.$nextTick(() => {
                    this.$forceUpdate();
                });
            },
            
            previousMonth() {
                if (this.month === 1) {
                    this.month = 12;
                    this.year--;
                } else {
                    this.month--;
                }
                this.updateCalendar();
            },
            
            nextMonth() {
                if (this.month === 12) {
                    this.month = 1;
                    this.year++;
                } else {
                    this.month++;
                }
                this.updateCalendar();
            },
            
            selectDate(day) {
                if (!day.currentMonth) return;
                
                const dateStr = this.formatDateISO(day.date);
                
                if (!this.startDate || (this.startDate && this.endDate)) {
                    // Start new selection
                    this.startDate = dateStr;
                    this.endDate = null;
                } else if (this.startDate && !this.endDate) {
                    // Select end date
                    if (day.date < new Date(this.startDate)) {
                        this.endDate = this.startDate;
                        this.startDate = dateStr;
                    } else {
                        this.endDate = dateStr;
                    }
                    
                    // Update Livewire
                    this.updateLivewire();
                }
            },
            
            clearSelection() {
                this.startDate = null;
                this.endDate = null;
                this.updateLivewire();
            },
            
            updateLivewire() {
                const wireElement = this.$el.closest('[wire\\:id]') || this.$el.closest('[wire:id]');
                if (wireElement) {
                    const wireId = wireElement.getAttribute('wire:id');
                    const livewireComponent = Livewire.find(wireId);
                    
                    if (livewireComponent) {
                        livewireComponent.set('filters.start_date', this.startDate);
                        livewireComponent.set('filters.end_date', this.endDate);
                        livewireComponent.set('calendarCurrentMonth', this.month);
                        livewireComponent.set('calendarCurrentYear', this.year);
                        
                        setTimeout(() => {
                            livewireComponent.loadPreviewData();
                        }, 100);
                    }
                }
            },
            
            formatDateISO(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            },
            
            formatDate(dateStr) {
                if (!dateStr) return '';
                const date = new Date(dateStr);
                return date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            }
        };
    }
</script>
