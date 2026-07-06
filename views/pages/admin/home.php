<div class="p-3 md:p-8">
    <div class="flex flex-col gap-4 md:gap-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl md:text-3xl font-bold tracking-tight text-foreground mb-2">ໜ້າຫຼັກ</h1>
                <p class="text-muted-foreground text-xs md:text-sm">ສະຫຼຸບສະຖານະການຂາຍ ແລະ ສາງສິນຄ້າ</p>
            </div>
            <form method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
                <div class="flex items-center gap-1.5">
                    <label class="text-[11px] font-bold text-muted-foreground whitespace-nowrap">ຈາກ</label>
                    <input type="date" name="from_date" value="<?= htmlspecialchars($fromDate ?? '') ?>"
                           class="flex-1 sm:flex-none px-3 py-2 rounded-xl border border-border text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                </div>
                <div class="flex items-center gap-1.5">
                    <label class="text-[11px] font-bold text-muted-foreground whitespace-nowrap">ຫາ</label>
                    <input type="date" name="to_date" value="<?= htmlspecialchars($toDate ?? '') ?>"
                           class="flex-1 sm:flex-none px-3 py-2 rounded-xl border border-border text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 sm:flex-none px-4 py-2 bg-primary text-white rounded-xl text-sm font-bold hover:opacity-90 transition-all">
                        <i class="fas fa-filter mr-1"></i>ກັ່ນຕອງ
                    </button>
                    <?php if ($fromDate || $toDate): ?>
                    <a href="<?= url('/admin') ?>" class="flex-1 sm:flex-none px-4 py-2 bg-gray-100 text-foreground/70 rounded-xl text-sm font-bold hover:bg-gray-200 transition-all text-center">
                        <i class="fas fa-times mr-1"></i>ລຶບ
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="grid gap-3 md:gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
            <div class="stat-card border-sky-200">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 stat-card-icon bg-gradient-to-br from-sky-50 to-sky-100 text-primary shadow-sm shadow-sky-200/30">
                        <i class="fas fa-box text-lg md:text-xl"></i>
                    </div>
                </div>
                <div class="space-y-1">
                    <h3 class="text-[11px] md:text-sm font-medium text-muted-foreground">ສິນຄ້າທັງໝົດ</h3>
                    <div class="text-2xl md:text-3xl font-black text-foreground"><?= number_format($stats['total_products']) ?></div>
                    <p class="text-[10px] md:text-xs text-muted-foreground">ລາຍການສິນຄ້າໃນລະບົບ</p>
                </div>
            </div>

            <div class="stat-card border-amber-200">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 stat-card-icon bg-gradient-to-br from-amber-50 to-amber-100 text-amber-500 shadow-sm shadow-amber-200/30">
                        <i class="fas fa-exclamation-triangle text-lg md:text-xl"></i>
                    </div>
                    <span class="text-[10px] md:text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg">ໃກ້ໝົດ</span>
                </div>
                <div class="space-y-1">
                    <h3 class="text-[11px] md:text-sm font-medium text-muted-foreground">ສິນຄ້າໃກ້ໝົດສາງ</h3>
                    <div class="text-2xl md:text-3xl font-black text-amber-600"><?= number_format($stats['low_stock']) ?></div>
                    <p class="text-[10px] md:text-xs text-muted-foreground">ຕ້ອງສັ່ງເພີ່ມ</p>
                </div>
            </div>

            <div class="stat-card border-emerald-200">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 stat-card-icon bg-gradient-to-br from-emerald-50 to-emerald-100 text-emerald-500 shadow-sm shadow-emerald-200/30">
                        <i class="fas fa-shopping-cart text-lg md:text-xl"></i>
                    </div>
                    <span class="text-[10px] md:text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">ມື້ນີ້</span>
                </div>
                <div class="space-y-1">
                    <h3 class="text-[11px] md:text-sm font-medium text-muted-foreground">ຍອດຂາຍມື້ນີ້</h3>
                    <div class="text-xl md:text-2xl font-black text-emerald-600"><?= number_format($stats['sales_today']) ?> <span class="text-xs md:text-sm">ກີບ</span></div>
                    <p class="text-[10px] md:text-xs text-muted-foreground">ລາຍຮັບຈາກການຂາຍ</p>
                </div>
            </div>

            <div class="stat-card border-violet-200">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 stat-card-icon bg-gradient-to-br from-violet-50 to-violet-100 text-violet-500 shadow-sm shadow-violet-200/30">
                        <i class="fas fa-users text-lg md:text-xl"></i>
                    </div>
                </div>
                <div class="space-y-1">
                    <h3 class="text-[11px] md:text-sm font-medium text-muted-foreground">ລູກຄ້າທັງໝົດ</h3>
                    <div class="text-2xl md:text-3xl font-black text-foreground"><?= number_format($stats['total_customers']) ?></div>
                    <p class="text-[10px] md:text-xs text-muted-foreground">ລູກຄ້າໃນລະບົບ</p>
                </div>
            </div>

            <div class="stat-card border-orange-200">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 stat-card-icon bg-gradient-to-br from-orange-50 to-orange-100 text-orange-500 shadow-sm shadow-orange-200/30">
                        <i class="fas fa-truck text-lg md:text-xl"></i>
                    </div>
                </div>
                <div class="space-y-1">
                    <h3 class="text-[11px] md:text-sm font-medium text-muted-foreground">ຜູ້ສະໜອງທັງໝົດ</h3>
                    <div class="text-2xl md:text-3xl font-black text-foreground"><?= number_format($stats['total_suppliers'] ?? 0) ?></div>
                    <p class="text-[10px] md:text-xs text-muted-foreground">ຜູ້ສະໜອງໃນລະບົບ</p>
                </div>
            </div>

            <div class="stat-card border-rose-200">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 stat-card-icon bg-gradient-to-br from-rose-50 to-rose-100 text-rose-500 shadow-sm shadow-rose-200/30">
                        <i class="fas fa-chart-line text-lg md:text-xl"></i>
                    </div>
                    <span class="text-[10px] md:text-xs font-bold text-rose-600 bg-rose-50 px-2 py-1 rounded-lg">ເດືອນນີ້</span>
                </div>
                <div class="space-y-1">
                    <h3 class="text-[11px] md:text-sm font-medium text-muted-foreground">ລາຍຮັບປະຈຳເດືອນ</h3>
                    <div class="text-lg md:text-xl font-black text-rose-600"><?= number_format($stats['monthly_revenue'] ?? 0) ?> <span class="text-xs md:text-sm">ກີບ</span></div>
                    <p class="text-[10px] md:text-xs text-muted-foreground">ລາຍຮັບທັງໝົດເດືອນນີ້</p>
                </div>
            </div>
        </div>

        <?php
        $days = [];
        $salesDayData = [];
        $salesDayMap = [];
        foreach ($stats['sales_by_day'] as $row) {
            $salesDayMap[$row['day']] = $row['total'];
        }

        if ($fromDate && $toDate) {
            $start = new \DateTime(min($fromDate, $toDate));
            $end = new \DateTime(max($fromDate, $toDate));
        } else {
            $start = new \DateTime('first day of this month');
            $end = new \DateTime('last day of this month');
        }
        $endInclude = clone $end;
        $endInclude->modify('+1 day');
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($start, $interval, $endInclude);

        foreach ($period as $dt) {
            $d = $dt->format('Y-m-d');
            $days[] = $d;
            $salesDayData[] = (int)($salesDayMap[$d] ?? 0);
        }

        $hasData = count($days) > 0;
        $maxVal = $hasData ? max(max($salesDayData), 1) : 1;

        $chartWidth = max(600, ($hasData ? count($days) : 30) * 55);
        $chartHeight = 300;
        $padTop = 30;
        $padBottom = 50;
        $padLeft = 70;
        $padRight = 20;
        $plotW = $chartWidth - $padLeft - $padRight;
        $plotH = $chartHeight - $padTop - $padBottom;
        $pointCount = count($days);
        $niceMax = ceil($maxVal / max(1, pow(10, floor(log10($maxVal))))) * pow(10, floor(log10($maxVal)));
        $ySteps = 5;
        $yStepVal = max(1, ceil($niceMax / $ySteps));
        $yStepPx = $plotH / $ySteps;

        function calcPoints2($data, $pointCount, $plotW, $plotH, $maxVal) {
            $pts = [];
            for ($i = 0; $i < $pointCount; $i++) {
                $x = ($i / max(1, $pointCount - 1)) * $plotW;
                $y = $plotH - (($data[$i] / max(1, $maxVal)) * $plotH);
                $pts[] = round($x, 1) . ',' . round($y, 1);
            }
            return implode(' ', $pts);
        }
        $salesPts = $hasData ? calcPoints2($salesDayData, $pointCount, $plotW, $plotH, $maxVal) : '';
        ?>

        <div class="bg-card rounded-2xl border p-4 md:p-6">
            <div class="flex items-center justify-between mb-4 md:mb-6">
                <div>
                    <h3 class="font-bold text-foreground text-sm md:text-base">ກຣາຟຍອດຂາຍ (ລາຍວັນ)</h3>
                    <p class="text-xs md:text-sm text-muted-foreground mt-1"><?= $fromDate && $toDate ? htmlspecialchars($fromDate) . ' ເຖິງ ' . htmlspecialchars($toDate) : 'ປະຈຳເດືອນ ' . date('m/Y') ?></p>
                </div>
            </div>

            <?php if ($hasData): ?>
            <div class="relative overflow-x-auto">
                <svg width="<?= $chartWidth ?>" height="<?= $chartHeight ?>" viewBox="0 0 <?= $chartWidth ?> <?= $chartHeight ?>" class="w-full" style="min-width: <?= $chartWidth ?>px">
                    <?php for ($i = 0; $i <= $ySteps; $i++):
                        $yY = $padTop + $i * $yStepPx;
                        $yVal = $niceMax - ($i * $yStepVal);
                    ?>
                    <line x1="<?= $padLeft ?>" y1="<?= $yY ?>" x2="<?= $chartWidth - $padRight ?>" y2="<?= $yY ?>" stroke="#f1f5f9" stroke-dasharray="4,4" stroke-width="1"/>
                    <text x="<?= $padLeft - 8 ?>" y="<?= $yY + 4 ?>" text-anchor="end" fill="#94a3b8" font-size="10"><?= number_format($yVal) ?></text>
                    <?php endfor; ?>

                    <?php for ($i = 0; $i < $pointCount; $i++):
                        if ($pointCount > 15 && $i % 3 !== 0) continue;
                        $xX = $padLeft + ($i / max(1, $pointCount - 1)) * $plotW;
                        $label = \DateTime::createFromFormat('Y-m-d', $days[$i])->format('d/m');
                    ?>
                    <text x="<?= $xX ?>" y="<?= $chartHeight - 12 ?>" text-anchor="middle" fill="#94a3b8" font-size="9"><?= $label ?></text>
                    <?php endfor; ?>

                    <defs>
                        <linearGradient id="salesGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#0ea5e9" stop-opacity="0.2"/>
                            <stop offset="100%" stop-color="#0ea5e9" stop-opacity="0.02"/>
                        </linearGradient>
                    </defs>

                    <polygon fill="url(#salesGrad)" points="<?= $padLeft ?>,<?= $padTop + $plotH ?> <?= $salesPts ?> <?= $padLeft + $plotW ?>,<?= $padTop + $plotH ?>"/>
                    <polyline fill="none" stroke="#0ea5e9" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round" points="<?= $salesPts ?>"/>

                    <?php for ($i = 0; $i < $pointCount; $i++):
                        $xX = $padLeft + ($i / max(1, $pointCount - 1)) * $plotW;
                        $yS = $padTop + $plotH - (($salesDayData[$i] / $maxVal) * $plotH);
                    ?>
                    <g class="cursor-pointer">
                        <circle cx="<?= $xX ?>" cy="<?= $yS ?>" r="4" fill="#0ea5e9" stroke="white" stroke-width="2"/>
                        <title>ຍອດຂາຍ: <?= number_format($salesDayData[$i]) ?> ກີບ</title>
                    </g>
                    <?php endfor; ?>
                </svg>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <p class="empty-state-title">ບໍ່ມີຂໍ້ມູນໃນໄລຍະນີ້</p>
                <p class="empty-state-desc">ລອງປ່ຽນຊ່ວງວັນທີໃໝ່</p>
            </div>
            <?php endif; ?>
        </div>

        <div class="grid gap-6 grid-cols-1 lg:grid-cols-7">
            <div class="lg:col-span-4 bg-card rounded-2xl border flex flex-col">
                <div class="p-4 md:p-6 border-b flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-foreground text-sm md:text-base">ລາຍການຂາຍຫຼ້າສຸດ</h3>
                        <p class="text-xs md:text-sm text-muted-foreground mt-1">ລາຍການທີ່ມີການເຄື່ອນໄຫວຫຼ້າສຸດ</p>
                    </div>
                    <a href="<?= url('/admin/sales') ?>" class="text-primary text-xs md:text-sm font-bold hover:underline">ເບິ່ງທັງໝົດ</a>
                </div>
                <div class="p-4 md:p-6 overflow-x-auto">
                    <div class="space-y-4 md:space-y-6">
                        <?php if (empty($stats['recent_sales'])): ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <p class="empty-state-title">ບໍ່ມີລາຍການຂາຍ</p>
                            <p class="empty-state-desc">ລອງປ່ຽນຊ່ວງວັນທີໃໝ່</p>
                        </div>
                        <?php else: foreach($stats['recent_sales'] as $s): ?>
                        <div class="flex items-center gap-3 md:gap-4">
                            <div class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                                <i class="fas fa-receipt text-xs md:text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0 space-y-0.5">
                                <p class="text-sm font-bold text-foreground leading-none truncate"><?= htmlspecialchars($s['customer_name'] ?? 'ລູກຄ້າທົ່ວໄປ') ?></p>
                                <p class="text-[11px] md:text-xs text-muted-foreground truncate">ໃບເກັບເງິນ #<?= htmlspecialchars($s['invoice_number']) ?> • <?= date('H:i', strtotime($s['created_at'])) ?></p>
                            </div>
                            <div class="ml-auto font-bold text-emerald-600 text-xs md:text-sm whitespace-nowrap"><?= number_format($s['grand_total']) ?> ກີບ</div>
                        </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3 bg-card rounded-2xl border">
                <div class="p-4 md:p-6 border-b">
                    <h3 class="font-bold text-foreground text-sm md:text-base">ສິນຄ້າຂາຍດີ</h3>
                    <p class="text-xs md:text-sm text-muted-foreground mt-1">ສິນຄ້າທີ່ຂາຍດີທີ່ສຸດ</p>
                </div>
                <div class="p-4 md:p-6 space-y-4 md:space-y-6">
                    <?php
                    $colors = ['bg-sky-500', 'bg-blue-500', 'bg-purple-500', 'bg-emerald-500', 'bg-amber-500'];
                    if (empty($stats['popular_products'])): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <p class="empty-state-title">ບໍ່ມີຂໍ້ມູນ</p>
                        <p class="empty-state-desc">ລອງປ່ຽນຊ່ວງວັນທີໃໝ່</p>
                    </div>
                    <?php else: foreach($stats['popular_products'] as $index => $item): ?>
                    <div class="flex items-center gap-3 md:gap-4">
                        <div class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-gray-100 flex items-center justify-center text-muted-foreground">
                            <i class="fas fa-box text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0 space-y-1.5 md:space-y-2">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-bold text-foreground truncate"><?= htmlspecialchars($item['name']) ?></p>
                                <span class="text-[11px] md:text-xs font-bold text-muted-foreground whitespace-nowrap ml-2"><?= $item['total_qty'] ?> ຊິ້ນ</span>
                            </div>
                            <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                                <div class="<?= $colors[$index] ?? 'bg-primary' ?> h-full transition-all duration-1000" style="width: <?= min(100, ($item['total_qty'] / 10) * 100) ?>%"></div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
