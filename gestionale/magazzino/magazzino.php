<?php
header('Access-Control-Allow-Origin: *');

/**
 *
 * @author     Paolo Randone
 * @author     <paolo.randone@croceverde.org>
* @version    8.2
 * @note       Powered for Croce Verde Torino. All rights reserved
 *
 */
session_start();
//connessione DB
include "../config/config.php";
if (($_SESSION["livello"])<4){
    header("Location: ../error.php");
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="author" content="Paolo Randone">
    <title>Giacenza magazzino</title>

    <? require "../config/include/header.html";?>

    <script>
        $(document).ready(function() {
            $('input[type="text"]').on('keypress', function() {
                var $this = $(this), value = $this.val();
                if (value.length === 1) {
                    $this.val( value.charAt(0).toUpperCase() );
                }
            });
            $('#additem').on('shown.bs.modal', function () {
                $('#nome').focus()
            });
            $('#submitButton').on('click', function(){
                $('#additem').modal('hide');
                var nome = $("#nome").val();
                var tipo = $("#tipo").val();
                var quantita = $("#quantita").val();
                var annomese = $("#scadenza").val();
                var scadenza = annomese+'-01';
                var dettagli = $("#dettagli").val();
                var posizione = $("#posizione").val();
                var categoria = $("#categoria").val();
                var fornitore = $("#fornitore").val();
                var prezzo = $("#prezzo").val();
                Swal.fire({
                    text: "Sei sicuro di voler aggiungere questo articolo?",
                    icon: "warning",
                    buttons:{
                        cancel:{
                            text: "Annulla",
                            value: null,
                            visible: true,
                            closeModal: true,
                        },
                        confirm:{
                            text: "Conferma",
                            value: true,
                            visible: true,
                            closeModal: true,
                        },
                    },
                })
                    .then((confirm) => {
                        if(confirm){
                            $.ajax({
                                url:"script.php",
                                type:"POST",
                                data:{nome:nome, tipo:tipo, quantita:quantita, scadenza:scadenza, dettagli:dettagli, posizione:posizione, categoria:categoria, fornitore:fornitore, prezzo:prezzo},
                                success:function(){
                                    Swal.fire({text:"Articolo inserito con successo", icon: "success", timer: 1000, button:false, closeOnClickOutside: false});
                                    setTimeout(function () {
                                            location.href='magazzino.php';
                                        },1001
                                    )
                                }
                            });
                        } else {
                            Swal.fire({text:"Operazione annullata come richiesto!", timer: 1000, button:false, closeOnClickOutside: false});
                        }
                    })
            })
        });
    </script>

    <script>
        $(document).ready(function(){
            $('[data-toggle="popover"]').popover();
        });
    </script>
    <!-- datatable -->
    <script>
        $(document).ready(function() {
            var dataTables = $('#myTable').DataTable({
                stateSave: true,
                "paging": false,
                "language": {url: '../config/js/package.json'},
                "order": [[1, "asc"]],
                "pagingType": "simple",
                "pageLength": 50,
                "columnDefs": [
                    {
                        "targets": [ 0 ],
                        "visible": true,
                        "searchable": false,
                        "orderable": false,
                    },
                    {
                        "targets": [ 6 ],
                        "visible": false,
                        "searchable": true,
                        "orderable": false,
                    }],
            });
            $('#reload').on('click', function () {
                dataTables
                    .search('')
                    .columns().search('')
                    .draw();
                location.reload();
            });
            //FILTRI TABELLA
            $('#consumo').on('click', function () {
                dataTables.columns(6).search("").draw();
                dataTables.columns(6).search("1").draw();
                $( "#consumo" ).removeClass( "btn-outline-secondary" ).addClass( "btn-secondary" );
                $( "#altro" ).removeClass( "btn-secondary" ).addClass( "btn-outline-secondary" );
                $( "#ricambi" ).removeClass( "btn-secondary" ).addClass( "btn-outline-secondary" );
                $( "#vestiario" ).removeClass( "btn-secondary" ).addClass( "btn-outline-secondary" );
                $( "#all" ).removeClass( "btn-secondary" ).addClass( "btn-outline-secondary" );
            });
            $('#altro').on('click', function () {
                dataTables.columns(6).search("").draw();
                dataTables.columns(6).search("3").draw();
                $( "#altro" ).removeClass( "btn-outline-secondary" ).addClass( "btn-secondary" );
                $( "#consumo" ).removeClass( "btn-secondary" ).addClass( "btn-outline-secondary" );
                $( "#ricambi" ).removeClass( "btn-secondary" ).addClass( "btn-outline-secondary" );
                $( "#vestiario" ).removeClass( "btn-secondary" ).addClass( "btn-outline-secondary" );
                $( "#all" ).removeClass( "btn-secondary" ).addClass( "btn-outline-secondary" );
            });
            $('#ricambi').on('click', function () {
                dataTables.columns(6).search("").draw();
                dataTables.columns(6).search("2").draw();
                $( "#ricambi" ).removeClass( "btn-outline-secondary" ).addClass( "btn-secondary" );
                $( "#altro" ).removeClass( "btn-secondary" ).addClass( "btn-outline-secondary" );
                $( "#consumo" ).removeClass( "btn-secondary" ).addClass( "btn-outline-secondary" );
                $( "#vestiario" ).removeClass( "btn-secondary" ).addClass( "btn-outline-secondary" );
                $( "#all" ).removeClass( "btn-secondary" ).addClass( "btn-outline-secondary" );
            });
            $('#vestiario').on('click', function () {
                dataTables.columns(6).search("").draw();
                dataTables.columns(6).search("4").draw();
                $( "#vestiario" ).removeClass( "btn-outline-secondary" ).addClass( "btn-secondary" );
                $( "#altro" ).removeClass( "btn-secondary" ).addClass( "btn-outline-secondary" );
                $( "#consumo" ).removeClass( "btn-secondary" ).addClass( "btn-outline-secondary" );
                $( "#ricambi" ).removeClass( "btn-secondary" ).addClass( "btn-outline-secondary" );
                $( "#all" ).removeClass( "btn-secondary" ).addClass( "btn-outline-secondary" );
            });
            $('#all').on('click', function () {
                dataTables.columns(6).search("").draw();
                dataTables.columns(6).search("").draw();
                $( "#altro" ).removeClass( "btn-secondary" ).addClass( "btn-outline-secondary" );
                $( "#ricambi" ).removeClass( "btn-secondary" ).addClass( "btn-outline-secondary" );
                $( "#consumo" ).removeClass( "btn-secondary" ).addClass( "btn-outline-secondary" );
                $( "#vestiario" ).removeClass( "btn-secondary" ).addClass( "btn-outline-secondary" );
                $( "#all" ).removeClass( "btn-outline-secondary" ).addClass( "btn-secondary" );
            });
        } );
    </script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="popover"]').popover();
            $('.fast').on('click', function (e) {
                e.preventDefault();
                var id = $(this).attr("id");
                var fastquantita = $(this).val();
                $.get("https://croceverde.org/gestionale/magazzino/qfast.php", {id:id, fastquantita:fastquantita}, function (html) {
                    $('#modalquantita').html(html);
                    $('.bd-quantita').modal('toggle');

                }).fail(function (msg) {
                    console.log(msg);
                })
            });
            $('.details').on('click', function (e) {
                e.preventDefault();
                var id = $(this).attr("id");
                $.get("https://croceverde.org/gestionale/magazzino/item.php", {id:id}, function (html) {
                    $('#modaldetails').html(html);
                    $('.bd-details').modal('toggle');

                }).fail(function (msg) {
                    console.log(msg);
                })
            });
            $('#export').on('click', function () {
                $('#modalexportITEMS').modal('show');

            })
        });
    </script>

</head>
<!-- NAVBAR -->
<div class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../index.php" style="color: #078f40">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php" style="color: #078f40">Autoparco</a></li>
            <li class="breadcrumb-item active" aria-current="page">Giacenza</li>
        </ol>
    </nav>
</div>
<!-- content -->
<body>
<div class="container-fluid">
    <div class="jumbotron">
<!--        <div style="text-align: center;">
            <div class="btn-group" role="group">
                <button id="consumo" type="button" class="btn btn-outline-secondary btn-sm">Materiale di consumo</button>
                <button id="ricambi" type="button" class="btn btn-outline-secondary btn-sm">Ricambi</button>
                <button id="altro" type="button" class="btn btn-outline-secondary btn-sm">Altro</button>
                <button id="vestiario" type="button" class="btn btn-outline-secondary btn-sm">Vestiario</button>
                <button id="all" type="button" class="btn btn-secondary btn-sm">ALL</button>
            </div>
            <div class="btn-group" role="group">
                <button id="reload" type="button" class="btn btn-outline-info btn-sm" ><i class="fas fa-sync-alt"></i></button>
                <button id="export" type="button" class="btn btn-outline-success btn-sm" ><i class="far fa-file-excel"></i></button>
            </div>
        </div>-->
        <div class="table-responsive-sm">
            <table class="table table-hover table-sm" id="myTable">
                <thead>
                <tr>
                    <th scope="col"><button class="btn btn-sm btn-outline-info" disabled style="border: none" data-toggle="modal" data-target="#additem"><i class="fas fa-plus"></i></button></th>
                    <th scope="col">Nome</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Quantità</th>
                    <th scope="col">Scadenza</th>
                    <th scope="col">Posizione</th>
                    <th scope="col">Categoria</th>
                </tr>
                </thead>
                <tbody>
                <?php

                $select = $db->query("SELECT * FROM giacenza order by nome, tipo");

                while($ciclo = $select->fetch_array()){
                    $today = date("Y-m");
                    $rif = strtotime("+2 months", strtotime($today));
                    $scadenza = strtotime($ciclo['scadenza']);
                    if($rif<$scadenza): ?>
                        <tr>
                            <td class="align-middle"><form><button type='button' id='<?=$ciclo['id']?>' class='btn-outline-dark btn btn-sm details' disabled><i class="fas fa-search"></i></button></form></td>
                            <td class="align-middle"><?=$ciclo['nome']?></td>
                            <td class="align-middle"><?=$ciclo['tipo']?></td>
                            <td class="align-middle"><form><button type='button' id='<?=$ciclo['id']?>' class='btn-link btn btn-sm fast' style="font-size:16px" value='<?=$ciclo['quantita']?>'><?=$ciclo['quantita']?></button></form></td>
                            <td class="align-middle"><?=substr($ciclo['scadenza'], 0, 7)?></td>
                            <td class="align-middle"><?=$ciclo['posizione']?></td>
                            <td class="align-middle"><?=$ciclo['categoria']?></td>
                        </tr>
                    <? endif; ?>
                    <? if($rif>=$scadenza): ?>
                        <tr>
                            <td class="align-middle"><form><button type='button' id='<?=$ciclo['id']?>' class='btn-outline-dark btn btn-sm details' disabled><i class="fas fa-search"></i></button></form></td>
                            <td class="align-middle"><?=$ciclo['nome']?></td>
                            <td class="align-middle"><?=$ciclo['tipo']?></td>
                            <td class="align-middle"><form><button type='button' id='<?=$ciclo['id']?>' class='btn-link btn btn-sm fast' style="font-size:16px" value="<?=$ciclo['quantita']?>"><?=$ciclo['quantita']?></button></form></td>
                            <td class="align-middle" style='color: red'><?=substr($ciclo['scadenza'], 0, 7)?></td>
                            <td class="align-middle"><?=$ciclo['posizione']?></td>
                            <td class="align-middle"><?=$ciclo['categoria']?></td>

                        </tr>
                    <? endif;
                } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL INSERISCI -->
<div class="modal" id="additem" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h6 class="modal-title" id="modal1Title">Aggiungi elemento</h6>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nome">Articolo</label>
                        <input id="nome" type="text" class="form-control form-control-sm" list="nomi" required>
                        <datalist id="nomi">
                            <?php
                            $select = $db->query("SELECT DISTINCT nome FROM giacenza");
                            while($ciclo = $select->fetch_array()){
                                echo "<option>".$ciclo['nome']."</option>";
                            }
                            ?>
                        </datalist>
                    </div> <!-- nome -->
                    <div class="form-group">
                        <label for="tipo">Tipo</label>
                        <input id="tipo" type="text" class="form-control form-control-sm" list="tipi">
                        <datalist id="tipi">
                            <?php
                            $select = $db->query("SELECT DISTINCT tipo FROM giacenza");
                            while($ciclo = $select->fetch_array()){
                                echo "<option>".$ciclo['tipo']."</option>";
                            }
                            ?>
                        </datalist>
                    </div> <!-- tipo -->
                    <div class="form-group">
                        <label for="quantita">Quantità</label>
                        <input id="quantita" class="form-control form-control-sm">
                    </div> <!-- quantita -->
                    <div class="form-group">
                        <label for="scadenza">Scadenza</label>
                        <input id="scadenza" class="form-control form-control-sm" placeholder="AAAA-MM" pattern="[0-9]{4}-(0[1-9]|1[012])">
                    </div> <!-- scadenza -->
                    <div class="form-group">
                        <label for="posizione">Posizione</label>
                        <input id="posizione" type="text" class="form-control form-control-sm">
                    </div> <!-- posizione -->
                    <div class="form-group">
                        <label for="categoria">Categoria</label>
                        <select id="categoria" class="form-control form-control-sm">
                            <option value="1">Materiale di consumo</option>
                            <option value="2">Ricambi</option>
                            <option value="3">Altro</option>
                            <option value="4">Vestiario</option>
                        </select>
                    </div> <!-- categoria -->
                    <div class="form-group">
                        <label for="fornitore">Fornitore</label>
                        <input id="fornitore" type="text" class="form-control form-control-sm" list="fornitori">
                        <datalist id="fornitori">
                            <?php
                            $select = $db->query("SELECT DISTINCT fornitore FROM giacenza");
                            while($ciclo = $select->fetch_array()){
                                echo "<option>".$ciclo['fornitore']."</option>";
                            }
                            ?>
                        </datalist>
                    </div> <!-- fornitore -->
                    <div class="form-group">
                        <label for="prezzo">Prezzo</label>
                        <input id="prezzo" type="text" class="form-control form-control-sm">
                    </div> <!-- prezzo -->
                    <div class="form-group">
                        <label for="dettagli">Dettagli</label>
                        <textarea class="form-control" type="text" id="dettagli" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Chiudi</button>
                    <button type="button" class="btn btn-outline-success btn-sm" id="submitButton">Salva</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal bd-quantita" role="dialog" aria-hidden="true" id="test">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body" id="modalquantita">
            </div>
        </div>
    </div>
</div>

<div class="modal bd-details" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body" id="modaldetails">
            </div>
        </div>
    </div>
</div>

<!--esporta MAGAZZINO-->
<div id="modalexportITEMS" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <form action="exportITEMS.php" method="post">
                <div class="modal-body" align="center">
                    <h6 class="modal-title">Esporta articoli</h6>
                    <br>
                    <input type="hidden" id="user_id" value="<?=$_SESSION['ID']?>">
                    <select id="selectcategoria" name="selectcategoria" class="form-control form-control-sm" required>
                        <option value="ALL">Tutti</option>
                        <option value="1">Materiale di consumo</option>
                        <option value="2">Ricambi</option>
                        <option value="3">Altro</option>
                        <option value="4">Vestiario</option>
                    </select>
                    <br>
                    <button type="submit" class="btn btn-success btn-sm" id="exportBTN" name="exportBTN"><i class="far fa-file-excel"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>

<?php include('../config/include/footer.php'); ?>

</html>