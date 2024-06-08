<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../css/antropometria.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <title>Cadastro de Antropometria</title>
</head>

<body>
    <!-- <nav>
        <h1>Antropometria para <?= ucwords($aluno['nome']) ?></h1>
    </nav> -->

    <div class="container">

        <div class="content">

            <div class="anamnese">
                <h2>Antropometria</h2>
                <h4><?= ucwords($aluno['nome']) ?></h4>
            </div>

            <form method="post" action="nova_antropometria.php?id=<?= $aluno['id'] ?>">

                <div class="col-12 mb-3 ">
                    <!-- Button trigger modal -->
                    <button type="button" class="btns btn-ajuda" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        Ajuda
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Dicas para fazer avaliação antropométrica</h1>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        A avaliação antropométrica (ou antropometria) é formada por um conjunto de medidas físicas, corporais, que são realizadas a partir de métodos já padronizados e valores de referência definidos. É possível realizar medidas primárias, como peso e altura, e a partir delas aferir medidas secundárias através de cálculos, como o IMC
                                    </p>
                                    <p>
                                        É um dos métodos mais indicados para auxiliar no diagnóstico, prevenção e identificação de riscos e possíveis alterações do estado nutricional, direcionando a intervenção do profissional.
                                    </p>
                                    <h4>Medições mais comuns</h4>
                                    <h5>Peso</h5>
                                    <p>
                                        A aferição é feita com uma balança que deve estar zerada e calibrada em um solo nivelado. O avaliado deve estar em pé, com os pés afastados ao centro da plataforma, com o olhar reto e fixo à frente.
                                    </p>
                                    <h5>Altura</h5>
                                    <p>
                                        Deve ser feita com um estadiômetro, ou utilizando uma trena. O avaliado deve estar descalço, de pé, ereto, com os braços estendidos ao lado do corpo, os olhos em um ponto fixo à frente, e a cabeça a 90 graus do chão. Os calcanhares e os joelhos devem estar unidos, e os glúteos, costas e cabeça encostados na parede ou no aparelho de medida.
                                    </p>
                                    <h5>Circunferências corporais</h5>
                                    <p>
                                        Trata-se da medição de vários perímetros corporais, como cintura, abdômen, pescoço, tórax, quadril, braços, antebraços, punhos, coxas e panturrilhas. Dentre elas, as mais comuns são as de cintura e de quadril.
                                    </p>
                                    <h5>Dobras cutâneas</h5>
                                    <p>
                                        As dobras cutâneas são medidas feitas com um aparelho chamado adipômetro, e servem para estimar o percentual de gordura corporal. As medidas são feitas diretamente na pele: o profissional “pinça” a pele do paciente, separando a gordura do tecido muscular.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btns " data-bs-dismiss="modal">Entendido</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion">Centrais <img class="vector" src="../img/Vector.png"> </div>
                <div class="panel">

                    <table>
                        <thead>
                            <tr>
                                <th>Peso e Estatura:</th>
                                <th>Perímetros (cm):</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td><input name="novo_peso" type="number" min="0" max="1000" step=".01" placeholder="Peso (kg)" required></td>

                                <td><input name="novo_torax" type="number" min="0" max="1000" step=".01" placeholder="Tórax" required></td>
                            </tr>

                            <tr>
                                <td><input name="nova_estatura" type="number" min="0" max="1000" step=".01" placeholder="Estatura" required></td>

                                <td><input name="nova_cintura" type="number" min="0" max="1000" step=".01" placeholder="Cintura" required></td>
                            </tr>

                            <tr>
                                <td class="hidden-cell"></td>

                                <td><input name="novo_abdomem" type="number" min="0" max="1000" step=".01" placeholder="Abdômen" required></td>
                            </tr>

                            <tr>
                                <td class="hidden-cell"></td>

                                <td><input name="novo_quadril" type="number" min="0" max="1000" step=".01" placeholder="Quadril" required></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="accordion">Periféricos <img class="vector" src="../img/Vector.png"> </div>
                <div class="panel">

                    <table>
                        <thead>
                            <tr>
                                <th>Periférico Direito</th>
                                <th>Periférico Esquerdo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input name="novo_braco_relaxado_direito" type="number" min="0" max="1000" step=".01" placeholder="Braço relaxado" required></td>
                                <td><input name="novo_braco_relaxado_esquerdo" type="number" min="0" max="1000" step=".01" placeholder="Braço relaxado" required></td>
                            </tr>

                            <tr>
                                <td><input name="novo_braco_contraido_direito" type="number" min="0" max="1000" step=".01" placeholder="Braço contraído direito" required></td>
                                <td><input name="novo_braco_contraido_esquerdo" type="number" min="0" max="1000" step=".01" placeholder="Braço contraído esquerdo" required></td>
                            </tr>

                            <tr>
                                <td><input name="novo_antebraco_direito" type="number" min="0" max="1000" step=".01" placeholder="Antebraço direito" required></td>
                                <td><input name="novo_antebraco_esquerdo" type="number" min="0" max="1000" step=".01" placeholder="Antebraço esquerdo" required></td>
                            </tr>

                            <tr>
                                <td><input name="nova_coxa_proximal_direita" type="number" min="0" max="1000" step=".01" placeholder="Coxa proximal direita" required></td>
                                <td><input name="nova_coxa_proximal_esquerda" type="number" min="0" max="1000" step=".01" placeholder="Coxa proximal esquerda" required></td>
                            </tr>

                            <tr>
                                <td><input name="nova_perna_direita" type="number" min="0" max="1000" step=".01" placeholder="Perna direita" required></td>
                                <td><input name="nova_perna_esquerda" type="number" min="0" max="1000" step=".01" placeholder="Perna esquerda" required></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="accordion">Dobras Cutâneas (mm) <img class="vector" src="../img/Vector.png"> </div>
                <div class="panel">

                    <table>
                        <thead>
                            <tr>
                                <th>Dobras cutâneas centrais</th>
                                <th>Dobras cutâneas periféricas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input name="nova_subescapular" type="number" min="0" max="1000" step=".01" placeholder="Subescapular" required></td>
                                <td><input name="novo_triceps" type="number" min="0" max="1000" step=".01" placeholder="Tríceps" required></td>
                            </tr>

                            <tr>
                                <td><input name="nova_axilar_medial_vertical" type="number" min="0" max="1000" step=".01" placeholder="Axilar medial vertical" required></td>
                                <td><input name="novo_biceps" type="number" min="0" max="1000" step=".01" placeholder="Bíceps" required></td>
                            </tr>

                            <tr>
                                <td><input name="nova_supra_iliaca_anterior" type="number" min="0" max="1000" step=".01" placeholder="Supra ilíaca anterior" required></td>
                                <td><input name="nova_coxa_proximal" type="number" min="0" max="1000" step=".01" placeholder="Coxa proximal" required></td>
                            </tr>

                            <tr>
                                <td><input name="nova_supra_iliaca_medial" type="number" min="0" max="1000" step=".01" placeholder="Supra ilíaca medial" required></td>
                                <td><input name="nova_coxa_medial" type="number" min="0" max="1000" step=".01" placeholder="Coxa medial" required></td>
                            </tr>

                            <tr>
                                <td><input name="novo_peitoral" type="number" min="0" max="1000" step=".01" placeholder="Peitoral" required></td>
                                <td><input name="nova_perna" type="number" min="0" max="1000" step=".01" placeholder="Perna" required></td>
                            </tr>

                            <tr>

                                <td><input name="novo_abdominal_vertical" type="number" min="0" max="1000" step=".01" placeholder="Abdominal vertical" required></td>

                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="accordion">Diâmetros Ósseos (mm) <img class="vector" src="../img/Vector.png"> </div>
                <div class="panel">

                    <table>
                        <thead>
                            <tr>
                                <th>Diâmetros Ósseos:</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input name="novo_biestiloide" type="number" min="0" max="1000" step=".01" placeholder="Biestiloide" required></td>
                            </tr>
                            <tr>
                                <td><input name="novo_biependicondilar_umeral" type="number" min="0" max="1000" step=".01" placeholder="Biependicondilar umeral" required></td>
                            </tr>

                            <tr>
                                <td><input name="novo_biependicondilar_femural" type="number" min="0" max="1000" step=".01" placeholder="Biependicondilar femural" required></td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                <div class="btns">
                    <button>Enviar</button>
                </div>
                <!-- <input type="submit" value="Enviar" /> -->
            </form>
        </div>

    </div>

    <script>
        var acc = document.getElementsByClassName("accordion");
        var panelp = document.getElementsByClassName("panel");
        var imgV = document.getElementsByClassName("vector");
        var i;

        for (i = 0; i < acc.length; i++) {

            acc[i].addEventListener("click", function() {


                let arat = [...Object.values(acc)];

                var panel = panelp[arat.indexOf(this)];

                // this.classList.toggle("active");
                this.setAttribute("class", "accordion active");

                panel.style.maxHeight = panel.scrollHeight + "px";

                imgV[arat.indexOf(this)].style.rotate = "180deg"

                for (x = 0; x < acc.length; x++) {
                    if (x != arat.indexOf(this)) {
                        panelp[x].style.maxHeight = null;

                        if (arat[x].classList[1] == "active") {
                            arat[x].classList.toggle("active");
                            imgV[x].style.rotate = "0deg"
                        }
                    }
                }

            });

        }
    </script>

</body>

</html>