function habilitarTextarea(habilitar) {
    var textarea = document.getElementById("outros_descricao");
    textarea.disabled = !habilitar;
}

function habilitarCampos(habilitar) {
    var textarea = document.getElementById("exercicio_frequencia");
    var inputText = document.getElementById("exercicio_tipo");
    textarea.disabled = !habilitar;
    inputText.disabled = !habilitar;
}


function habilitarDescricaoMedicamentos(habilitar) {
    var descricaoTextarea = document.getElementById("medicamentos_descricao");
    descricaoTextarea.disabled = !habilitar;
}

function habilitarDescricaoCirurgia(habilitar) {
    var descricaoTextarea = document.getElementById("cirurgia_descricao");
    descricaoTextarea.disabled = !habilitar;
}

function habilitarDescricaoOutros(habilitar) {
    var textareaOutros = document.getElementById("historico_descricao");
    textareaOutros.disabled = !habilitar;
}

function habilitarProblemasOsteoArticulres(habilitar) {
    var textareaProblemasOsteoarticulares = document.getElementById("problemas_osteoarticulares_descricao");
    textareaProblemasOsteoarticulares.disabled = !habilitar;
}