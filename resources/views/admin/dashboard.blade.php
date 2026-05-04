<x-layouts.admin title="Admin Dashboard" header="Dashboard" subtitle="Ringkasan bisnis hari ini">
    <div
        x-data="{
            stats: null,
            charts: null,
            salesChart: null,
            topChart: null,
            statusChart: null,
            paymentChart: null,
            hourlyChart: null,
            async load() {
                const res = await fetch(@js(route('admin.dashboard.data')));
                if (!res.ok) return;
                const json = await res.json();
                this.stats = json.stats;
                this.charts = json.charts;
                this.$nextTick(() => this.renderCharts());
            },
            renderCharts() {
                if (!this.charts) return;
                const ctx1 = document.getElementById('sales7d')?.getContext('2d');
                const ctx2 = document.getElementById('topProducts')?.getContext('2d');
                const ctx3 = document.getElementById('ordersByStatus')?.getContext('2d');
                const ctx4 = document.getElementById('paymentMethods')?.getContext('2d');
                const ctx5 = document.getElementById('salesHourly')?.getContext('2d');
                if (!ctx1 || !ctx2 || !ctx3 || !ctx4 || !ctx5 || !window.Chart) return;

                this.salesChart?.destroy?.();
                this.topChart?.destroy?.();
                this.statusChart?.destroy?.();
                this.paymentChart?.destroy?.();
                this.hourlyChart?.destroy?.();

                this.salesChart = new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: this.charts.sales_7d.labels,
                        datasets: [{
                            label: 'Omzet',
                            data: this.charts.sales_7d.data,
                            borderColor: '#8B5E3C',
                            backgroundColor: 'rgba(139,94,60,0.16)',
                            tension: 0.35,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { ticks: { callback: (v) => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) } } }
                    }
                });

                this.topChart = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: this.charts.top_products.labels,
                        datasets: [{
                            label: 'Qty',
                            data: this.charts.top_products.data,
                            backgroundColor: 'rgba(139,94,60,0.14)',
                            borderColor: 'rgba(139,94,60,0.35)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });

                this.statusChart = new Chart(ctx3, {
                    type: 'doughnut',
                    data: {
                        labels: this.charts.orders_by_status.labels,
                        datasets: [{
                            data: this.charts.orders_by_status.data,
                            backgroundColor: [
                                'rgba(139,94,60,0.9)',
                                'rgba(59,130,246,0.85)',
                                'rgba(168,85,247,0.85)',
                                'rgba(245,158,11,0.9)',
                                'rgba(16,185,129,0.9)',
                                'rgba(244,63,94,0.85)',
                            ],
                            borderColor: 'rgba(255,255,255,0.0)',
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, boxHeight: 10, usePointStyle: true, pointStyle: 'circle' } } },
                        cutout: '68%',
                    }
                });

                this.paymentChart = new Chart(ctx4, {
                    type: 'doughnut',
                    data: {
                        labels: this.charts.payment_methods.labels,
                        datasets: [{
                            data: this.charts.payment_methods.data,
                            backgroundColor: ['rgba(139,94,60,0.9)', 'rgba(14,165,233,0.85)'],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, boxHeight: 10, usePointStyle: true, pointStyle: 'circle' } } },
                        cutout: '70%',
                    }
                });

                this.hourlyChart = new Chart(ctx5, {
                    type: 'bar',
                    data: {
                        labels: this.charts.sales_today_hourly.labels,
                        datasets: [{
                            label: 'Omzet',
                            data: this.charts.sales_today_hourly.data,
                            backgroundColor: 'rgba(139,94,60,0.16)',
                            borderColor: 'rgba(139,94,60,0.35)',
                            borderWidth: 1,
                            borderRadius: 10,
                            maxBarThickness: 18,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { ticks: { callback: (v) => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) } } }
                    }
                });
            }
        }"
        x-init="load()"
        class="space-y-6"
    >
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-3xl border border-black/5 bg-white/80 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Total Penjualan Hari Ini</div>
                        <div class="mt-2 text-xl font-semibold" x-text="stats ? ('Rp ' + new Intl.NumberFormat('id-ID').format(stats.today_sales)) : '—'"></div>
                    </div>
                    <div class="grid h-10 w-10 place-items-center rounded-2xl bg-coffee-600/10 text-coffee-700 ring-1 ring-coffee-600/15 dark:bg-cream-200/10 dark:text-cream-200 dark:ring-cream-200/15">
                        <x-heroicon-o-banknotes class="h-5 w-5" />
                    </div>
                </div>
            </div>
            <div class="rounded-3xl border border-black/5 bg-white/80 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Total Order Hari Ini</div>
                        <div class="mt-2 text-xl font-semibold" x-text="stats ? stats.today_orders : '—'"></div>
                    </div>
                    <div class="grid h-10 w-10 place-items-center rounded-2xl bg-sky-500/10 text-sky-700 ring-1 ring-sky-500/15 dark:bg-sky-400/10 dark:text-sky-300 dark:ring-sky-300/15">
                        <x-heroicon-o-receipt-percent class="h-5 w-5" />
                    </div>
                </div>
            </div>
            <div class="rounded-3xl border border-black/5 bg-white/80 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Omzet Bulan Ini</div>
                        <div class="mt-2 text-xl font-semibold" x-text="stats ? ('Rp ' + new Intl.NumberFormat('id-ID').format(stats.month_sales)) : '—'"></div>
                    </div>
                    <div class="grid h-10 w-10 place-items-center rounded-2xl bg-emerald-500/10 text-emerald-700 ring-1 ring-emerald-500/15 dark:bg-emerald-400/10 dark:text-emerald-300 dark:ring-emerald-300/15">
                        <x-heroicon-o-chart-bar class="h-5 w-5" />
                    </div>
                </div>
            </div>
            <div class="rounded-3xl border border-black/5 bg-white/80 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Produk Habis</div>
                        <div class="mt-2 text-xl font-semibold" x-text="stats ? stats.out_of_stock : '—'"></div>
                    </div>
                    <div class="grid h-10 w-10 place-items-center rounded-2xl bg-rose-500/10 text-rose-700 ring-1 ring-rose-500/15 dark:bg-rose-400/10 dark:text-rose-300 dark:ring-rose-300/15">
                        <x-heroicon-o-exclamation-triangle class="h-5 w-5" />
                    </div>
                </div>
            </div>
            <div class="rounded-3xl border border-black/5 bg-white/80 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Pesanan Pending</div>
                        <div class="mt-2 text-xl font-semibold" x-text="stats ? stats.pending_orders : '—'"></div>
                    </div>
                    <div class="grid h-10 w-10 place-items-center rounded-2xl bg-amber-500/10 text-amber-700 ring-1 ring-amber-500/15 dark:bg-amber-400/10 dark:text-amber-300 dark:ring-amber-300/15">
                        <x-heroicon-o-clock class="h-5 w-5" />
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-4 xl:grid-cols-2">
            <div class="rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                <div class="flex items-center justify-between">
                    <div class="text-sm font-semibold">Penjualan 7 Hari</div>
                    <a href="{{ route('admin.reports.index') }}" class="text-sm font-semibold text-coffee-700 hover:underline dark:text-cream-200">Lihat laporan</a>
                </div>
                <div class="mt-4 h-56 w-full overflow-hidden">
                    <canvas id="sales7d" class="w-full"></canvas>
                </div>
            </div>
            <div class="rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                <div class="text-sm font-semibold">Produk Terlaris (30 hari)</div>
                <div class="mt-4 h-56 w-full overflow-hidden">
                    <canvas id="topProducts" class="w-full"></canvas>
                </div>
            </div>
        </div>

        <div class="grid gap-4 xl:grid-cols-3">
            <div class="rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                <div class="text-sm font-semibold">Omzet Per Jam (Hari Ini)</div>
                <div class="mt-4 h-56 w-full overflow-hidden">
                    <canvas id="salesHourly" class="w-full"></canvas>
                </div>
            </div>
            <div class="rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                <div class="text-sm font-semibold">Order by Status (Hari Ini)</div>
                <div class="mt-4 h-56 w-full overflow-hidden">
                    <canvas id="ordersByStatus" class="w-full"></canvas>
                </div>
            </div>
            <div class="rounded-3xl border border-black/5 bg-white/70 p-5 shadow-sm backdrop-blur dark:border-white/10 dark:bg-gray-950/40">
                <div class="text-sm font-semibold">Metode Pembayaran (30 Hari)</div>
                <div class="mt-4 h-56 w-full overflow-hidden">
                    <canvas id="paymentMethods" class="w-full"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    @endpush
</x-layouts.admin>
