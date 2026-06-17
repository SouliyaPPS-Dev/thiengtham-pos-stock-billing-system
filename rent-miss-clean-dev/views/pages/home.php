<div class="p-3 md:p-8"> 
    <div class="flex flex-col gap-4 md:gap-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"> 
            <div>
                <h1 class="text-xl md:text-3xl font-bold tracking-tight text-gray-800 mb-2">ສະຫຼຸບລາຍຮັບ-ລາຍຈ່າຍ</h1>
                <p class="text-gray-500 text-xs md:text-sm">ສະຫຼຸບສະຖານະການເງິນຂອງຮ້ານ</p> 
            </div>
            <form method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
                <div class="flex items-center gap-1.5">
                    <label class="text-[11px] font-bold text-gray-400 whitespace-nowrap">ຈາກວັນທີ</label>
                    <input type="date" name="from_date" value="<?= htmlspecialchars($fromDate ?? '') ?>"
                           class="flex-1 sm:flex-none px-3 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                </div>
                <div class="flex items-center gap-1.5">
                    <label class="text-[11px] font-bold text-gray-400 whitespace-nowrap">ຫາວັນທີ</label>
                    <input type="date" name="to_date" value="<?= htmlspecialchars($toDate ?? '') ?>"
                           class="flex-1 sm:flex-none px-3 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 sm:flex-none px-4 py-2 bg-primary text-white rounded-xl text-sm font-bold hover:opacity-90 transition-all">
                        <i class="fas fa-filter mr-1"></i>ກັ່ນຕອງ
                    </button>
                    <?php if ($fromDate || $toDate): ?>
                    <a href="<?= url('/') ?>" class="flex-1 sm:flex-none px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-sm font-bold hover:bg-gray-200 transition-all text-center">
                        <i class="fas fa-times mr-1"></i>ລຶບ
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <!-- Income/Expense Summary Cards -->
        <div class="grid gap-3 md:gap-4 grid-cols-1 sm:grid-cols-3">
            <div class="bg-white rounded-2xl border border-emerald-200 p-4 md:p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500">
                        <i class="fas fa-arrow-up text-lg md:text-xl"></i>
                    </div>
                    <span class="text-[10px] md:text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg"><?= $fromDate && $toDate ? 'ຕາມໄລຍະ' : 'ເດືອນນີ້' ?></span>
                </div>
                <div class="space-y-1">
                    <h3 class="text-[11px] md:text-sm font-medium text-gray-500">ລາຍຮັບທັງໝົດ (Income)</h3>
                    <div class="text-xl md:text-2xl font-black text-emerald-600"><?= number_format($stats['revenue_month']) ?> <span class="text-xs md:text-sm">ກີບ</span></div>
                    <p class="text-[10px] md:text-xs text-gray-400"><?= $fromDate && $toDate ? 'ລາຍຮັບຕາມໄລຍະທີ່ເລືອກ' : 'ລາຍຮັບຈາກຄ່າເຊົ່າຊຸດໄໝ' ?></p>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl border border-red-200 p-4 md:p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-500">
                        <i class="fas fa-arrow-down text-lg md:text-xl"></i>
                    </div>
                    <span class="text-[10px] md:text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded-lg"><?= $fromDate && $toDate ? 'ຕາມໄລຍະ' : 'ເດືອນນີ້' ?></span>
                </div>
                <div class="space-y-1">
                    <h3 class="text-[11px] md:text-sm font-medium text-gray-500">ລາຍຈ່າຍທັງໝົດ (Expenses)</h3>
                    <div class="text-xl md:text-2xl font-black text-red-600"><?= number_format($stats['expense_month']) ?> <span class="text-xs md:text-sm">ກີບ</span></div>
                    <p class="text-[10px] md:text-xs text-gray-400"><?= $fromDate && $toDate ? 'ລາຍຈ່າຍຕາມໄລຍະທີ່ເລືອກ' : 'ຄ່າໃຊ້ຈ່າຍປະຈຳເດືອນ' ?></p>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl border border-sky-200 p-4 md:p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 <?= ($stats['revenue_month'] - $stats['expense_month']) >= 0 ? 'bg-sky-50 text-sky-500' : 'bg-red-50 text-red-500' ?> rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-pie text-lg md:text-xl"></i>
                    </div>
                    <span class="text-[10px] md:text-xs font-bold <?= ($stats['revenue_month'] - $stats['expense_month']) >= 0 ? 'text-sky-600 bg-sky-50' : 'text-red-600 bg-red-50' ?> px-2 py-1 rounded-lg">ກຳໄລ</span>
                </div>
                <div class="space-y-1">
                    <h3 class="text-[11px] md:text-sm font-medium text-gray-500">ກຳໄລສຸດທິ (Net Profit)</h3>
                    <div class="text-xl md:text-2xl font-black <?= ($stats['revenue_month'] - $stats['expense_month']) >= 0 ? 'text-sky-600' : 'text-red-600' ?>"><?= number_format($stats['revenue_month'] - $stats['expense_month']) ?> <span class="text-xs md:text-sm">ກີບ</span></div>
                    <p class="text-[10px] md:text-xs text-gray-400"><?= $fromDate && $toDate ? 'ກຳໄລສຸດທິຕາມໄລຍະ' : 'ລາຍຮັບຫັກລາຍຈ່າຍ' ?></p>
                </div>
            </div>
        </div>
        
        <!-- Inventory Stats Cards -->
        <div class="grid gap-3 md:gap-4 grid-cols-2 md:grid-cols-4">
            <div class="bg-white rounded-2xl border p-4 md:p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-sky-50 rounded-xl flex items-center justify-center text-primary">
                        <i class="fas fa-tshirt text-lg md:text-xl"></i>
                    </div>
                </div>
                <div class="space-y-1">
                    <h3 class="text-[11px] md:text-sm font-medium text-gray-500">ຈຳນວນຊຸດທັງໝົດ</h3>
                    <div class="text-2xl md:text-3xl font-black text-gray-800"><?= number_format($stats['total_products']) ?></div>
                    <p class="text-[10px] md:text-xs text-gray-400">ຊຸດໄໝທັງໝົດໃນລະບົບ</p>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl border p-4 md:p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500">
                        <i class="fas fa-hand-holding-heart text-lg md:text-xl"></i>
                    </div>
                    <span class="text-[10px] md:text-xs font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded-lg">ກຳລັງເຊົ່າ</span>
                </div>
                <div class="space-y-1">
                    <h3 class="text-[11px] md:text-sm font-medium text-gray-500">ກຳລັງຖືກເຊົ່າ</h3>
                    <div class="text-2xl md:text-3xl font-black text-gray-800"><?= number_format($stats['rented_out']) ?></div>
                    <p class="text-[10px] md:text-xs text-gray-400">ລໍຖ້າການສົ່ງຄືນ</p>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl border p-4 md:p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-500">
                        <i class="fas fa-check-circle text-lg md:text-xl"></i>
                    </div>
                    <span class="text-[10px] md:text-xs font-bold text-green-500 bg-green-50 px-2 py-1 rounded-lg">ຫວ່າງ</span>
                </div>
                <div class="space-y-1">
                    <h3 class="text-[11px] md:text-sm font-medium text-gray-500">ຊຸດທີ່ຫວ່າງ</h3>
                    <div class="text-2xl md:text-3xl font-black text-gray-800"><?= number_format($stats['available']) ?></div>
                    <p class="text-[10px] md:text-xs text-gray-400">ພ້ອມໃຫ້ເຊົ່າທັນທີ</p>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl border p-4 md:p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-500">
                        <i class="fas fa-money-bill-wave text-lg md:text-xl"></i>
                    </div>
                    <span class="text-[10px] md:text-xs font-bold text-purple-500 bg-purple-50 px-2 py-1 rounded-lg">ມື້ນີ້</span>
                </div>
                <div class="space-y-1">
                    <h3 class="text-[11px] md:text-sm font-medium text-gray-500">ລາຍຮັບມື້ນີ້</h3>
                    <div class="text-xl md:text-2xl font-black text-gray-800"><?= number_format($stats['revenue_today']) ?> <span class="text-xs md:text-sm">ກີບ</span></div>
                    <p class="text-[10px] md:text-xs text-gray-400">ລາຍຮັບຈາກການເຊົ່າ</p>
                </div>
            </div>
        </div>
        
        <!-- Income vs Expense Line Chart (Daily) -->
        <?php 
        $days = [];
        $incomeDayData = [];
        $expenseDayData = [];
        
        $incomeDayMap = [];
        foreach ($stats['income_by_day'] as $row) {
            $incomeDayMap[$row['day']] = $row['total'];
        }
        $expenseDayMap = [];
        foreach ($stats['expense_by_day'] as $row) {
            $expenseDayMap[$row['day']] = $row['total'];
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
            $incomeDayData[] = (int)($incomeDayMap[$d] ?? 0);
            $expenseDayData[] = (int)($expenseDayMap[$d] ?? 0);
        }
        
        $hasData = count($days) > 0;
        $maxVal = $hasData ? max(max($incomeDayData), max($expenseDayData), 1) : 1;
        
        $chartWidth = max(600, ($hasData ? count($days) : 30) * 55);
        $chartHeight = 300;
        
        // SVG line chart dimensions
        $padTop = 30;
        $padBottom = 50;
        $padLeft = 70;
        $padRight = 20;
        $plotW = $chartWidth - $padLeft - $padRight;
        $plotH = $chartHeight - $padTop - $padBottom;
        $pointCount = count($days);
        
        // Y-axis label generator
        $niceMax = ceil($maxVal / max(1, pow(10, floor(log10($maxVal))))) * pow(10, floor(log10($maxVal)));
        $ySteps = 5;
        $yStepVal = max(1, ceil($niceMax / $ySteps));
        $yStepPx = $plotH / $ySteps;
        
        // Build SVG polyline points
        function calcPoints($data, $pointCount, $plotW, $plotH, $maxVal) {
            $pts = [];
            for ($i = 0; $i < $pointCount; $i++) {
                $x = ($i / max(1, $pointCount - 1)) * $plotW;
                $y = $plotH - (($data[$i] / max(1, $maxVal)) * $plotH);
                $pts[] = round($x, 1) . ',' . round($y, 1);
            }
            return implode(' ', $pts);
        }
        $incomePts = $hasData ? calcPoints($incomeDayData, $pointCount, $plotW, $plotH, $maxVal) : '';
        $expensePts = $hasData ? calcPoints($expenseDayData, $pointCount, $plotW, $plotH, $maxVal) : '';
        
        // Net profit line
        $profitDayData = [];
        for ($i = 0; $i < $pointCount; $i++) {
            $profitDayData[] = $incomeDayData[$i] - $expenseDayData[$i];
        }
        // For profit, we need to handle negative values - shift to a dual baseline
        // Use the same scale, but zero line should be in the middle
        $profitMin = min(0, min($profitDayData));
        $profitMax = max(0, max($profitDayData));
        $profitRange = max(1, $profitMax - $profitMin);
        $profitPts = '';
        if ($hasData) {
            $pts = [];
            for ($i = 0; $i < $pointCount; $i++) {
                $x = ($i / max(1, $pointCount - 1)) * $plotW;
                $y = $plotH - (($profitDayData[$i] - $profitMin) / $profitRange) * $plotH;
                $pts[] = round($x, 1) . ',' . round($y, 1);
            }
            $profitPts = implode(' ', $pts);
        }
        ?>
        
        <div class="bg-white rounded-2xl border p-4 md:p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4 md:mb-6">
                <div>
                    <h3 class="font-bold text-gray-800 leading-none text-sm md:text-base">ກຣາຟລາຍຮັບ-ລາຍຈ່າຍ (ລາຍວັນ)</h3>
                    <p class="text-xs md:text-sm text-gray-500 mt-1"><?= $fromDate && $toDate ? htmlspecialchars($fromDate) . ' ເຖິງ ' . htmlspecialchars($toDate) : 'ປະຈຳເດືອນ ' . date('m/Y') ?></p>
                </div>
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 md:w-4 h-0.5 rounded bg-emerald-500"></span>
                        <span class="text-[10px] md:text-[11px] font-bold text-gray-500">ລາຍຮັບ</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 md:w-4 h-0.5 rounded bg-red-400"></span>
                        <span class="text-[10px] md:text-[11px] font-bold text-gray-500">ລາຍຈ່າຍ</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 md:w-4 h-0.5 rounded bg-blue-500"></span>
                        <span class="text-[10px] md:text-[11px] font-bold text-gray-500">ກຳໄລສຸດທິ</span>
                    </div>
                </div>
            </div>
            
            <?php if ($hasData): ?>
            <div class="relative overflow-x-auto">
                <svg width="<?= $chartWidth ?>" height="<?= $chartHeight ?>" viewBox="0 0 <?= $chartWidth ?> <?= $chartHeight ?>" class="w-full" style="min-width: <?= $chartWidth ?>px">
                    <!-- Y-axis grid lines -->
                    <?php for ($i = 0; $i <= $ySteps; $i++): 
                        $yY = $padTop + $i * $yStepPx;
                        $yVal = $niceMax - ($i * $yStepVal);
                    ?>
                    <line x1="<?= $padLeft ?>" y1="<?= $yY ?>" x2="<?= $chartWidth - $padRight ?>" y2="<?= $yY ?>" stroke="#f1f5f9" stroke-dasharray="4,4" stroke-width="1"/>
                    <text x="<?= $padLeft - 8 ?>" y="<?= $yY + 4 ?>" text-anchor="end" class="text-[10px]" fill="#94a3b8" font-size="10"><?= number_format($yVal) ?></text>
                    <?php endfor; ?>
                    
                    <!-- X-axis labels (show every 5th to avoid crowding) -->
                    <?php for ($i = 0; $i < $pointCount; $i++): 
                        if ($pointCount > 15 && $i % 3 !== 0) continue;
                        $xX = $padLeft + ($i / max(1, $pointCount - 1)) * $plotW;
                        $label = \DateTime::createFromFormat('Y-m-d', $days[$i])->format('d/m');
                    ?>
                    <text x="<?= $xX ?>" y="<?= $chartHeight - 12 ?>" text-anchor="middle" fill="#94a3b8" font-size="9"><?= $label ?></text>
                    <?php endfor; ?>
                    
                    <!-- Zero line for profit (if negative values exist) -->
                    <?php if ($profitMin < 0): 
                        $zeroY = $padTop + (0 - $profitMin) / $profitRange * $plotH;
                    ?>
                    <line x1="<?= $padLeft ?>" y1="<?= $zeroY ?>" x2="<?= $chartWidth - $padRight ?>" y2="<?= $zeroY ?>" stroke="#cbd5e1" stroke-width="1" stroke-dasharray="2,3"/>
                    <?php endif; ?>
                    
                    <!-- Income area fill -->
                    <defs>
                        <linearGradient id="incomeGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#10b981" stop-opacity="0.2"/>
                            <stop offset="100%" stop-color="#10b981" stop-opacity="0.02"/>
                        </linearGradient>
                        <linearGradient id="expenseGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#f87171" stop-opacity="0.2"/>
                            <stop offset="100%" stop-color="#f87171" stop-opacity="0.02"/>
                        </linearGradient>
                        <linearGradient id="profitGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.15"/>
                            <stop offset="100%" stop-color="#3b82f6" stop-opacity="0.02"/>
                        </linearGradient>
                    </defs>
                    
                    <!-- Income area + line -->
                    <polygon fill="url(#incomeGrad)" points="<?= $padLeft ?>,<?= $padTop + $plotH ?> <?= $incomePts ?> <?= $padLeft + $plotW ?>,<?= $padTop + $plotH ?>"/>
                    <polyline fill="none" stroke="#10b981" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round" points="<?= $incomePts ?>" class="cursor-pointer"/>
                    
                    <!-- Expense area + line -->
                    <polygon fill="url(#expenseGrad)" points="<?= $padLeft ?>,<?= $padTop + $plotH ?> <?= $expensePts ?> <?= $padLeft + $plotW ?>,<?= $padTop + $plotH ?>"/>
                    <polyline fill="none" stroke="#f87171" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round" points="<?= $expensePts ?>" class="cursor-pointer"/>
                    
                    <!-- Profit area + line -->
                    <?php if ($profitPts): ?>
                    <polygon fill="url(#profitGrad)" points="<?= $padLeft ?>,<?= $padTop + $plotH ?> <?= $profitPts ?> <?= $padLeft + $plotW ?>,<?= $padTop + $plotH ?>"/>
                    <polyline fill="none" stroke="#3b82f6" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" stroke-dasharray="5,3" points="<?= $profitPts ?>" class="cursor-pointer"/>
                    <?php endif; ?>
                    
                    <!-- Data dots with hover tooltips -->
                    <?php for ($i = 0; $i < $pointCount; $i++): 
                        $xX = $padLeft + ($i / max(1, $pointCount - 1)) * $plotW;
                        $yI = $padTop + $plotH - (($incomeDayData[$i] / $maxVal) * $plotH);
                        $yE = $padTop + $plotH - (($expenseDayData[$i] / $maxVal) * $plotH);
                        $yP = $padTop + $plotH - (($profitDayData[$i] - $profitMin) / $profitRange) * $plotH;
                    ?>
                    <!-- Income dot -->
                    <g class="cursor-pointer">
                        <circle cx="<?= $xX ?>" cy="<?= $yI ?>" r="4" fill="#10b981" stroke="white" stroke-width="2" class="hover:r-[6] transition-all"/>
                        <title>ລາຍຮັບ: <?= number_format($incomeDayData[$i]) ?> ກີບ</title>
                    </g>
                    <!-- Expense dot -->
                    <g class="cursor-pointer">
                        <circle cx="<?= $xX ?>" cy="<?= $yE ?>" r="4" fill="#f87171" stroke="white" stroke-width="2"/>
                        <title>ລາຍຈ່າຍ: <?= number_format($expenseDayData[$i]) ?> ກີບ</title>
                    </g>
                    <!-- Profit dot -->
                    <g class="cursor-pointer">
                        <circle cx="<?= $xX ?>" cy="<?= $yP ?>" r="3" fill="#3b82f6" stroke="white" stroke-width="1.5"/>
                        <title>ກຳໄລ: <?= number_format($profitDayData[$i]) ?> ກີບ</title>
                    </g>
                    <?php endfor; ?>
                </svg>
            </div>
            <?php else: ?>
            <div class="h-[<?= $chartHeight ?>px] flex items-center justify-center text-gray-400">
                <div class="text-center">
                    <i class="fas fa-chart-line text-4xl text-gray-200 mb-2"></i>
                    <p class="font-bold">ບໍ່ມີຂໍ້ມູນໃນໄລຍະນີ້</p>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Total summary row -->
            <div class="mt-4 pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-6 text-xs">
                <div class="flex items-center gap-2">
                    <span class="text-gray-400">ລວມລາຍຮັບ</span>
                    <span class="font-black text-emerald-600"><?= number_format(array_sum($incomeDayData)) ?> ກີບ</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-gray-400">ລວມລາຍຈ່າຍ</span>
                    <span class="font-black text-red-500"><?= number_format(array_sum($expenseDayData)) ?> ກີບ</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-gray-400">ກຳໄລສຸດທິ</span>
                    <span class="font-black <?= (array_sum($incomeDayData) - array_sum($expenseDayData)) >= 0 ? 'text-sky-600' : 'text-red-600' ?>"><?= number_format(array_sum($incomeDayData) - array_sum($expenseDayData)) ?> ກີບ</span>
                </div>
            </div>
        </div>
        
        <div class="grid gap-6 grid-cols-1 lg:grid-cols-7">
            <!-- Recent Rentals -->
            <div class="lg:col-span-4 bg-white rounded-2xl border flex flex-col">
                <div class="p-4 md:p-6 border-b flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-800 leading-none text-sm md:text-base">ລາຍການເຊົ່າຫຼ້າສຸດ</h3>
                        <p class="text-xs md:text-sm text-gray-500 mt-1">ລາຍການທີ່ມີການເຄື່ອນໄຫວຫຼ້າສຸດ</p>
                    </div>
                    <a href="<?= url('/rentals') ?>" class="text-primary text-xs md:text-sm font-bold hover:underline">ເບິ່ງທັງໝົດ</a>
                </div>
                <div class="p-4 md:p-6 overflow-x-auto">
                    <div class="space-y-4 md:space-y-6">
                        <?php if (empty($stats['recent_rentals'])): ?>
                        <p class="text-center text-gray-400 py-4">ບໍ່ມີລາຍການເຊົ່າຫຼ້າສຸດ</p>
                        <?php else: foreach($stats['recent_rentals'] as $r): ?>
                        <div class="flex items-center gap-3 md:gap-4">
                            <div class="relative flex h-9 w-9 md:h-10 md:w-10 shrink-0 overflow-hidden rounded-full ring-2 ring-gray-100 bg-primary/10 flex items-center justify-center text-primary">
                                <i class="fas fa-user text-xs md:text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0 space-y-0.5">
                                <p class="text-sm font-bold text-gray-800 leading-none truncate"><?= htmlspecialchars($r['customer_name']) ?></p>
                                <p class="text-[11px] md:text-xs text-gray-500 truncate">ບິນເລກທີ: <?= htmlspecialchars($r['invoice_number']) ?> • <?= date('H:i', strtotime($r['created_at'])) ?></p>
                            </div>
                            <div class="ml-auto font-bold text-emerald-600 text-xs md:text-sm whitespace-nowrap">+<?= number_format($r['grand_total']) ?> ກີບ</div>
                        </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Popular Items -->
            <div class="lg:col-span-3 bg-white rounded-2xl border">
                <div class="p-4 md:p-6 border-b">
                    <h3 class="font-bold text-gray-800 leading-none text-sm md:text-base">ຊຸດໄໝຍອດນິຍົມ</h3>
                    <p class="text-xs md:text-sm text-gray-500 mt-1">ຊຸດທີ່ຖືກເຊົ່າຫຼາຍທີ່ສຸດ</p>
                </div>
                <div class="p-4 md:p-6 space-y-4 md:space-y-6">
                    <?php 
                    $colors = ['bg-sky-500', 'bg-blue-500', 'bg-purple-500'];
                    $placeholder = 'https://ui-avatars.com/api/?name=No+Image&background=f3f4f6&color=64748b&size=100';
                    if (empty($stats['popular_items'])): ?>
                    <p class="text-center text-gray-400 py-4">ບໍ່ມີຂໍ້ມູນຊຸດຍອດນິຍົມ</p>
                    <?php else: foreach($stats['popular_items'] as $index => $item): 
                        $imgUrl = !empty($item['image']) ? $item['image'] : $placeholder;
                    ?>
                    <div class="flex items-center gap-3 md:gap-4">
                        <img src="<?= $imgUrl ?>" 
                             class="h-12 w-12 md:h-14 md:w-14 rounded-xl object-cover shadow-sm ring-1 ring-gray-100 cursor-pointer hover:scale-110 transition-transform"
                             @click="previewImage('<?= $imgUrl ?>')">
                        <div class="flex-1 min-w-0 space-y-1.5 md:space-y-2">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-bold text-gray-800 truncate"><?= htmlspecialchars($item['name']) ?></p>
                                <span class="text-[11px] md:text-xs font-bold text-gray-500 whitespace-nowrap ml-2"><?= $item['rental_count'] ?> ຄັ້ງ</span>
                            </div>
                            <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                                <div class="<?= $colors[$index] ?? 'bg-primary' ?> h-full transition-all duration-1000" style="width: <?= min(100, ($item['rental_count'] / 10) * 100) ?>%"></div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

 