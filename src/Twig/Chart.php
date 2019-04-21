<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-04-21
 * Time: 02:35
 */

namespace App\Twig;

use App\Chart\Type\Bar\BarChart;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class Chart extends AbstractExtension {
    public function getFunctions(): array {
        return [
            new TwigFunction('chart', [$this, 'renderChart'], ['is_safe' => ['html']]),
        ];
    }

    public function renderChart($chart, $type = 'bar'): string {
        if ($chart instanceof BarChart) {
            $labels = json_encode($chart->getLabels(), true);
            $datasets = json_encode($chart->getDatasets(), true);
            $uniqueId = uniqid("chart-", true);

            return '
                <canvas id="'.$uniqueId.'" class="chartjs" width="undefined" height="undefined"></canvas>
                <script>
                    window.addEventListener("load", function () {
                        var myLineChart = new Chart(document.getElementById("'.$uniqueId.'"), {
                            type: \'bar\',
                            data: {
                                labels: '.$labels.',
                                datasets: '.$datasets.'
                                
                            }
                        });
                    });

                </script>
                ';
        }
    }
}