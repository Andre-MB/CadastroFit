<?php include 'pdf_header.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório do Aluno</title>
    <link rel="stylesheet" type="text/css" href="../css/relatorio.css">
    <!-- Inclua o jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Inclua a biblioteca html2pdf -->
    <script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>

    <!-- Biblioteca de gráficos -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <!-- Script jQuery para enviar o formulário quando a data for alterada -->
    <script>
    $(document).ready(function () {
        $("#downloadPdf").click(function () {
            var clonedBody = document.body.cloneNode(true);

            // Remove os elementos indesejados do clone
            $(clonedBody).find("nav").remove();

            // Estilize o clone para melhorar o layout no PDF
            $(clonedBody).find("body").css({
                "margin": "20px",
                "padding": "20px",
                "box-sizing": "border-box"
            });

            // Converta os gráficos em imagens e insira no clone do documento
            convertIMCChartToImage(clonedBody);
            convertAntropometriaPieChartToImage(clonedBody);

            // Use a biblioteca html2pdf para gerar o PDF a partir do clone
            html2pdf(clonedBody, {
                filename: 'Relatório completo.pdf'
            });
        });
    });

    function convertIMCChartToImage(clonedBody) {
        var imcChartImage = document.createElement('img');
        imcChartImage.src = document.getElementById('grafico-imc').toDataURL();
        imcChartImage.style.width = '100%';
        clonedBody.querySelector('#grafico-imc').replaceWith(imcChartImage);
    }

    function convertAntropometriaPieChartToImage(clonedBody) {
        var antropometriaPieChartImage = document.createElement('img');
        antropometriaPieChartImage.src = document.getElementById('antropometriaPieChart').toDataURL();
        antropometriaPieChartImage.style.width = '100%';
        clonedBody.querySelector('#antropometriaPieChart').replaceWith(antropometriaPieChartImage);
    }
</script>

</head>
<body>

<nav>
    <!-- Botão para baixar em PDF -->
    <button type="button" id="downloadPdf">Baixar em PDF</button>

    <button onclick="window.location.href='../dashboards/detalhes_aluno.php?id=<?= $id_aluno ?>'">Voltar</button>
</nav>

<div class="pdf-container">

    <!-- Dados do Aluno -->
    <section class="section">
        <h1>Relatório do Aluno</h1>

        <section class="subsection">
            <h2>Dados Pessoais</h2>
            <table>
                <tr>
                    <td class="with-margin"><strong>Nome:</strong> <?= ucwords($aluno['nome']) ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <strong>Sexo:</strong> <?= ucwords($aluno['sexo']) ?>
                    </td>
                </tr>
                <tr>
                    <td class="with-margin"><strong>Telefone:</strong><?= $aluno['telefone'] ?></td>
                </tr>
                <tr>
                    <td class="with-margin"> <strong>Avaliador:</strong> <?php echo $_SESSION['nome_admin']; ?></td>
                </tr>
            </table>
        </section>

        <!-- Detalhes da Anamnese -->
        <section class="subsection">
            <h2>Anamnese</h2>
            <table>
                <tr>
                    <?php foreach ($anamneses as $anamnese) : ?>
                        <td>
                            <table>
                                <tr>
                                    <td class="with-margin"><strong>Data:</strong><?=formatarData($anamnese['created_at']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Objetivos:</strong> <?=ucwords($_SESSION['objetivos']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Diabetes:</strong> <?= $anamnese['diabetes'] ?? 'N/A' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Cardiopatia:</strong> <?= $anamnese['cardiopatia'] ?? 'N/A' ?></td>
                                </tr>
                            </table>
                        </td>
                    <?php endforeach; ?>
                </tr>
            </table>
        </section>

        <!-- Detalhes da Antropometria -->
        <section class="subsection">
            <h2>Antropometria</h2>
            <table>
                <tr>
                    <?php foreach ($antropometrias as $antropometria) : ?>
                        <td>
                            <table>
                                <tbody>
                                    <tr>
                                        <td><strong>Data:</strong> <?= formatarData($antropometria['created_at']) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Braço Relaxado Direito:</strong> <?= $antropometria['braco_relaxado_direito'] ?? 'N/A' ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Braço Relaxado Esquerdo:</strong> <?= $antropometria['braco_relaxado_esquerdo'] ?? 'N/A' ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>IMC:</strong> <?= $antropometria['imc'] ?? 'N/A' ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>ID:</strong> <?= $antropometria['id']?? 'N/A'?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    <?php endforeach; ?>    
                </tr>    
            </table>
        </section>
        
        <div class="graph-container">
            <canvas classe="imc" id="grafico-imc" width="300" height="300" style="max-width: 300px; max-height: 300px;"></canvas>
            <canvas id="antropometriaPieChart" width="300" height="300" style="max-width: 300px; max-height: 300px; margin: 0 50px;"></canvas>
        </div>

<?php
    $imcLegends = []; // Array para armazenar as legendas das barras
    foreach ($antropometrias as $antropometria) {
        if ($antropometria['imc'] !== null) {
            // Define a cor e a legenda com base no valor do IMC
            if ($antropometria['imc'] < 18.5) {
                $imcColors[] = 'rgba(0, 191, 255, 0.5)'; // Azul claro
                $imcLegends[] = 'Abaixo do peso';
            } elseif ($antropometria['imc'] >= 18.5 && $antropometria['imc'] <= 24.9) {
                $imcColors[] = 'rgba(144, 238, 144, 0.5)'; // Verde
                $imcLegends[] = 'Peso normal';
            } elseif ($antropometria['imc'] >= 25.0 && $antropometria['imc'] <= 29.9) {
                $imcColors[] = 'rgba(255, 255, 0, 0.5)'; // Amarelo claro
                $imcLegends[] = 'Sobrepeso';
            } elseif ($antropometria['imc'] >= 30.0 && $antropometria['imc'] <= 34.9) {
                $imcColors[] = 'rgba(255, 140, 0, 0.5)'; // Laranja queimado
                $imcLegends[] = 'Obesidade Classe 1';
            } elseif ($antropometria['imc'] >= 35.0 && $antropometria['imc'] <= 39.9) {
                $imcColors[] = 'rgba(255, 69, 0, 0.5)'; // Laranja queimado
                $imcLegends[] = 'Obesidade Classe 2';
            } else {
                $imcColors[] = 'rgba(220, 20, 60, 0.5)'; // Vermelho
                $imcLegends[] = 'Obesidade Classe 3';
            }
        }
    }
    ?>

<script>
    $(document).ready(function () {
    // Dados do gráfico
    var dates = <?php echo json_encode($dates); ?>;
    var imcValues = <?php echo json_encode($imcValues); ?>;
    var imcLegends = <?php echo json_encode($imcLegends); ?>

    // Função para obter cor e legenda com base no IMC
    function getCorELabel(imc) {
        if (imc < 18.5) {
            return { cor: 'rgba(0, 191, 255, 0.5)', label: 'Abaixo do peso' };
        } else if (imc >= 18.5 && imc <= 24.9) {
            return { cor: 'rgba(144, 238, 144, 0.5)', label: 'Peso normal' };
        } else if (imc >= 25.0 && imc <= 29.9) {
            return { cor: 'rgba(255, 255, 0, 0.5)', label: 'Sobrepeso' };
        } else if (imc >= 30.0 && imc <= 34.9) {
            return { cor: 'rgba(255, 140, 0, 0.5)', label: 'Obesidade Classe 1' };
        } else if (imc >= 35.0 && imc <= 39.9) {
            return { cor: 'rgba(255, 69, 0, 0.5)', label: 'Obesidade Classe 2' };
        } else {
            return { cor: 'rgba(220, 20, 60, 0.5)', label: 'Obesidade Classe 3' };
        }
    }

     // Combine as datas e as legendas em uma única matriz
     var combinedLabels = [];
        for (var i = 0; i < dates.length; i++) {
            combinedLabels.push(dates[i] + ": " + imcLegends[i]);
        }
    // Configurações do gráfico
    var ctx = document.getElementById('grafico-imc').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar', // Alterando o tipo de gráfico para barra
        data: {
            labels: combinedLabels,
            datasets: [{
                label: 'IMC',
                data: imcValues,
                backgroundColor: imcValues.map(function(imc) {
                    return getCorELabel(imc).cor;
                }),
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'IMC'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                annotation: {
                    annotations: imcValues.map(function(imc, index) {
                        var corELabel = getCorELabel(imc);
                        return {
                            type: 'line',
                            mode: 'horizontal',
                            scaleID: 'y',
                            value: imc,
                            borderColor: corELabel.cor,
                            borderWidth: 2,
                            label: {
                                enabled: true,
                                content: corELabel.label,
                                position: 'end'
                            }
                        };
                    })
                }
            }
        }
    });
});

</script>

        <script>
            $(document).ready(function () {
            // Dados para o gráfico de pizza
            var datas = [];
            var bracoDireito = [];
            var bracoEsquerdo = [];

            <?php foreach ($antropometrias as $antropometria) : ?>
                datas.push('<?= formatarData($antropometria['created_at']) ?>');
                bracoDireito.push(<?= $antropometria['braco_relaxado_direito'] ?? '0' ?>);
                bracoEsquerdo.push(<?= $antropometria['braco_relaxado_esquerdo'] ?? '0' ?>);
            <?php endforeach; ?>

            var ctx = document.getElementById('antropometriaPieChart').getContext('2d');
            var antropometriaPieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: datas,
                    datasets: [{
                    label: 'Braço Relaxado Direito',
                    data: bracoDireito,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    hoverOffset: 0, // Espaço ao passar o mouse
                    hoverBorderColor: 'black', // Cor da borda ao passar o mouse
                    hoverBorderWidth: 2 // Largura da borda ao passar o mouse
                    }, {
                    label: 'Braço Relaxado Esquerdo',
                    data: bracoEsquerdo,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    hoverOffset: 5, // Espaço ao passar o mouse
                    hoverBorderColor: 'black', // Cor da borda ao passar o mouse
                    hoverBorderWidth: 2 // Largura da borda ao passar o mouse
                    }]
                },
                options: {
                    plugins: {
                    tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = datas[context.dataIndex] + ': ' + context.dataset.label + ': ' + context.dataset.data[context.dataIndex];
                            return label;
                                }
                            }
                        }
                    }

                }
            });

        });
        </script>

        <!-- Detalhes dos Testes Físicos -->
        <section class="subsection">
            <h2>Testes Físicos</h2>
            <table>
                <tr>
                    <?php foreach ($testesFisicos as $testeFisico) : ?>
                        <td>
                            <table>
                                <tr>
                                    <td><strong>Data:</strong> <?= formatarData($testeFisico['created_at']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Banco de Wells:</strong> <?= $testeFisico['banco_de_wells'] ?? 'N/A' ?></td>
                                </tr>
                            </table>
                        </td>
                    <?php endforeach; ?>
                </table>
            </tr>
        </section>

        <section class="subsection">
    <h2>Cálculos</h2>
    <table>
        <tr>
            <th>Data</th>
            <th>Fórmula</th>
            <th>Resultado</th>
        </tr>
        <?php foreach ($calculos as $calculo) : ?>
            <tr>
                <td><?= formatarData($calculo['data']) ?></td>
                <td><?= $calculo['formula'] ?></td>
                <td><?= $calculo['resultado_porcentagem'].'%' ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
    </section>
</div>

</body>
</html>