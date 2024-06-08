<?php

// Consulta para verificar se há objetivos cadastrados na tabela anamnese
$stmtObjetivos = $conn->prepare("SELECT objetivos FROM anamnese WHERE aluno_id = ? AND objetivos IS NOT NULL AND objetivos != ''");
$stmtObjetivos->execute([$aluno_id]);
$anamneseObjetivos = $stmtObjetivos->fetch(PDO::FETCH_ASSOC);
$objetivos_cadastrados = $anamneseObjetivos ? $anamneseObjetivos['objetivos'] : null;

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wvgfNzswE9P8e5+lFlugBfGTtkNt9AiFudlXeMfdz5Ff+6jpxjVnGBFlOELKDad8" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/anamnes.css">
    <title>Cadastro de Anamnese</title>
</head>

<body>
    <!-- <nav>
        <h1>Anamnese para <?= ucwords($aluno['nome']) ?></h1>
    </nav> -->

    <form method="post" action="nova_anamnese.php?id=<?= $aluno['id'] ?>">

        <div class="container">
            <div class="content">

                <div class="anamnese">
                    <h2>Anamnese</h2>
                    <h4><?= ucwords($aluno['nome']) ?></h4>
                </div>

                <div class="accordion">Objetivos <img class="vector" src="../img/Vector.png"> </div>
                <div class="panel">
                    <div class="form-section">
                        <div class="">
                            <textarea name="objetivos" placeholder="Objetivos"></textarea>
                        </div>
                    </div>
                </div>

                <div class="accordion">Doenças Crônicas <img class="vector" src="../img/Vector.png"> </div>

                <div class="panel">
                    <div class="radio-group">
                        <span> Diabetes:</span>
                        <div class="mb-3">
                            <input type="radio" id="diabetes_nao" name="nova_diabetes" value="Não">
                            <label for="diabetes_nao">Não</label>

                            <input type="radio" id="diabetes_sim" name="nova_diabetes" value="Sim">
                            <label for="diabetes_sim">Sim</label>

                        </div>
                    </div>

                    <div class="radio-group">
                        <span>Cardiopatia:</span>
                        <div class="mb-3">
                            <input type="radio" id="cardiopatia_nao" name="nova_cardiopatia" value="Não">
                            <label for="cardiopatia_nao">Não</label>

                            <input type="radio" id="cardiopatia_sim" name="nova_cardiopatia" value="Sim">
                            <label for="cardiopatia_sim">Sim</label>
                        </div>
                    </div>

                    <div class="radio-group">
                        <span for="hipertensao">Hipertensão:</span>
                        <div class="mb-3">
                            <input type="radio" name="nova_hipertensao" id="hipertensao_nao" value="Não">
                            <label for="hipertensao_nao">Não</label>

                            <input type="radio" name="nova_hipertensao" id="hipertensao_sim" value="Sim">
                            <label for="hipertensao_sim">Sim</label>
                        </div>
                    </div>

                    <div class="radio-group">
                        <span for="outras_doencas">Outras:</span>
                        <div class="mb-3">
                            <input type="radio" name="nova_outras_doencas" id="outras_doencas_nao" value="Não" onclick="habilitarTextarea(false)">
                            <label for="outras_doencas_nao">Não</label>

                            <input type="radio" name="nova_outras_doencas" id="outras_doencas_sim" value="Sim" onclick="habilitarTextarea(true)">
                            <label for="outras_doencas_sim">Sim</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <textarea name="nova_doencas_cronicas_outros_descricao" id="outros_descricao" placeholder="Descrição de outras doenças crônicas" rows="3" disabled></textarea>
                    </div>
                </div>

                <div class="accordion">Hábitos de Vida <img class="vector" src="../img/Vector.png"> </div>
                <div class="panel">
                    <div class="radio-group">
                        <span for="fumante">Fumante:</span>
                        <div class="mb-3">
                            <input type="radio" name="novo_fumante" id="fumante_nao" value="Não">
                            <label for="fumante_nao">Não</label>

                            <input type="radio" name="novo_fumante" id="fumante_sim" value="Sim">
                            <label for="fumante_sim">Sim</label>
                        </div>
                    </div>

                    <div class="radio-group">
                        <span for="bebidas_alcoolicas">Consome bebidas alcoólicas:</span>
                        <div class="mb-3">
                            <input type="radio" name="nova_bebidas_alcoolicas" id="bebidas_alcoolicas_nao" value="Não">
                            <label for="bebidas_alcoolicas_nao">Não</label>

                            <input type="radio" name="nova_bebidas_alcoolicas" id="bebidas_alcoolicas_sim" value="Sim">
                            <label for="bebidas_alcoolicas_sim">Sim</label>
                        </div>
                    </div>

                    <div class="radio-group">
                        <span for="exercicio_regular">Pratica exercício físico regular:</span>
                        <div class="mb-3">
                            <input type="radio" name="novo_exercicio_regular" id="exercicio_regular_nao" value="Não" onclick="habilitarCampos(false)">
                            <label for="exercicio_regular_nao">Não</label>

                            <input type="radio" name="novo_exercicio_regular" id="exercicio_regular_sim" value="Sim" onclick="habilitarCampos(true)">
                            <label for="exercicio_regular_sim">Sim</label>
                        </div>
                    </div>

                    <p>Se sim, qual?</p>
                    <textarea name="novo_exercicio_frequencia" id="exercicio_frequencia" placeholder="Tipo de exercício" rows="3" disabled></textarea>

                    <p>Com que frequência?</p>
                    <input type="text" name="novo_exercicio_tipo" id="exercicio_tipo" placeholder="Frequência de exercício" disabled>
                </div>

                <div class="accordion">Medicamentos <img class="vector" src="../img/Vector.png"> </div>
                <div class="panel">
                    <div class="radio-group">
                        <span for="medicamentos">Você toma medicamentos?</span>
                        <div class="mb-3">
                            <input type="radio" name="novo_medicamentos" id="medicamentos_nao" value="Não" onclick="habilitarDescricaoMedicamentos(false)">
                            <label for="medicamentos_nao">Não</label>

                            <input type="radio" name="novo_medicamentos" id="medicamentos_sim" value="Sim" onclick="habilitarDescricaoMedicamentos(true)">
                            <label for="medicamentos_sim">Sim</label>
                        </div>
                    </div>

                    <p>Se sim, qual?</p>
                    <textarea name="novo_medicamentos_descricao" id="medicamentos_descricao" placeholder="Descrição dos medicamentos" rows="3" disabled></textarea>
                </div>

                <div class="accordion">Cirurgia <img class="vector" src="../img/Vector.png"> </div>
                <div class="panel">
                    <div class="">
                        <div class="radio-group-yes-no">
                            <span>Você fez um cirurgia?</span>

                            <div class="mb-3">

                                <div class="no">
                                    <input type="radio" name="nova_cirurgia" id="cirurgia_nao" value=Não onclick="habilitarDescricaoCirurgia(false)">
                                    <label for="cirurgia_nao">Não</label>

                                </div>

                                <div class="yes">
                                    <input type="radio" name="nova_cirurgia" id="cirurgia_sim" value=Sim onclick="habilitarDescricaoCirurgia(true)">
                                    <label for="cirurgia_sim">Sim</label>
                                </div>
                            </div>

                        </div>

                    </div>
                    <p>Se sim, qual?</p>
                    <textarea name="nova_cirurgia_descricao" id="cirurgia_descricao" placeholder="Descrição da cirurgia" rows="3" disabled></textarea>

                </div>

                <div class="accordion">Histórico Familiar <img class="vector" src="../img/Vector.png"> </div>
                <div class="panel">
                    <div class="mt-1 mb-1">
                        <span for="historico_diabetes">Diabetes:</span>
                        <div class="radio-group-yes-no">
                            <input type="radio" name="novo_historico_diabetes" id="historico_diabetes_nao" value="Não">
                            <label for="historico_diabetes_nao" value="Não">Não</label>

                            <input type="radio" name="novo_historico_diabetes" id="historico_diabetes_sim" value="Sim">
                            <label for="historico_diabetes_sim" value="Sim">Sim</label>
                        </div>
                    </div>

                    <div class="mb-1">
                        <span for="historico_cardiopatia">Cardiopatia:</span>
                        <div class="radio-group-yes-no">
                            <input type="radio" name="novo_historico_cardiopatia" id="historico_cardiopatia_nao" value="Não">
                            <label for="historico_cardiopatia_nao" value="Não">Não</label>

                            <input type="radio" name="novo_historico_cardiopatia" id="historico_cardiopatia_sim" value="Sim">
                            <label for="historico_cardiopatia_sim" value="Sim">Sim</label>
                        </div>
                    </div>

                    <div class="mb-1">

                        <span for="historico_hipertensao">Hipertensão:</span>
                        <div class="radio-group-yes-no">
                            <input type="radio" name="novo_historico_hipertensao" id="historico_hipertensao_nao" value="Não">
                            <label for="historico_hipertensao_nao" value="Não">Não</label>

                            <input type="radio" name="novo_historico_hipertensao" id="historico_hipertensao_sim" value="Sim">
                            <label for="historico_hipertensao_sim" value="Sim">Sim</label>
                        </div>

                    </div>


                    <div class="mb-1">
                        <span for="historico_cancer">Câncer:</span>
                        <div class="radio-group-yes-no">
                            <input type="radio" name="novo_historico_cancer" id="historico_cancer_nao" value="Não">
                            <label for="historico_cancer_nao" value="Não">Não</label>

                            <input type="radio" name="novo_historico_cancer" id="historico_cancer_sim" value="Sim">
                            <label for="historico_cancer_sim" value="Sim">Sim</label>
                        </div>
                    </div>

                    <div class="mb-1">
                        <span for="historico_outros">Outros:</span>
                        <div class="radio-group-yes-no">
                            <input type="radio" name="novo_historico_outros" id="historico_outros_nao" value="Não" onclick="habilitarDescricaoOutros(false)">
                            <label for="historico_outros_nao" value="Não">Não</label>

                            <input type="radio" name="novo_historico_outros" id="historico_outros_sim" value="Sim" onclick="habilitarDescricaoOutros(true)">
                            <label for="historico_outros_sim" value="Sim">Sim</label>
                        </div>
                    </div>

                    <textarea name="novo_historico_descricao" id="historico_descricao" placeholder="Histórico Familiar" rows="3" disabled></textarea>


                </div>

                <div class="accordion">Problemas Osteoarticulares <img class="vector" src="../img/Vector.png"> </div>
                <div class="panel">
                    <div class="radio-group-yes-no">
                        <span>Você tem problemas osteoarticulares ?</span>

                        <div class="mb-3">

                            <div class="no">
                                <input type="radio" name="novo_problemas_osteoarticulares" id="problemas_osteoarticulares_nao" value="Não" onclick="habilitarProblemasOsteoArticulres(false)">
                                <label for="problemas_osteoarticulares_nao" value="Não">Não</label>
                            </div>

                            <div class="yes">

                                <input type="radio" name="novo_problemas_osteoarticulares" id="problemas_osteoarticulares_sim" value="Sim" onclick="habilitarProblemasOsteoArticulres(true)">
                                <label for="problemas_osteoarticulares_sim" value="Sim">Sim</label>
                            </div>
                        </div>
                    </div>

                    <textarea name="novo_problemas_osteoarticulares_descricao" id="problemas_osteoarticulares_descricao" placeholder="Descrição dos Problemas Osteoarticulares" rows="3" disabled></textarea>
                </div>

                <div class="btns">
                    <button>Enviar</button>
                </div>
            </div>

        </div>

    </form>

    <!-- Adicione links ou botões para outras partes do seu aplicativo, se necessário -->
    <script src="../script/anamnese.js"></script>
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