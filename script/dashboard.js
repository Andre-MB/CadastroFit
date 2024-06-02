$(document).ready(function () {
        // Adiciona eventos de clique aos cabeçalhos da tabela para ordenação
        $('th').click(function () {
            var table = $(this).parents('table').eq(0);
            var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()));
            this.asc = !this.asc;
            if (!this.asc) {
                rows = rows.reverse();
            }
            for (var i = 0; i < rows.length; i++) {
                table.append(rows[i]);
            }
        });

        // Função para comparar os valores das colunas para ordenação
        function comparer(index) {
            return function (a, b) {
                var valA = getCellValue(a, index);
                var valB = getCellValue(b, index);
                return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB);
            };
        }

        // Função para obter o valor da célula
        function getCellValue(row, index) {
            return $(row).children('td').eq(index).text();
        }

         // Adiciona evento de input ao campo de pesquisa
        $('#search').on('input', function () {
        filterTable($(this).val());
        });

        // Função para filtrar a tabela com base no valor de pesquisa
        function filterTable(query) {
            var table = $('.content table');
            table.find('tr:gt(0)').each(function () {
                var row = $(this);
                var name = row.find('td:eq(0)').text().toLowerCase(); // índice 0 corresponde à coluna do nome
                if (name.includes(query.toLowerCase())) {
                row.show();
                } else {
                row.hide();
                }
            });
        }

    });

    document.addEventListener('DOMContentLoaded', function () {
        // Get all table rows
        var rows = document.querySelectorAll('table tr');
    
        // Add click event listeners to each row
        rows.forEach(function (row) {
            row.addEventListener('click', function () {
                // Toggle a class on the clicked row
                this.classList.toggle('clicked');
            });
        });
    });