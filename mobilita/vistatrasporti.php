<?php
/**
 *
 * @author     Paolo Randone
 * @author     <paolo.randone@croceverde.org>
* @version    8.1
 * @note       Powered for Croce Verde Torino. All rights reserved
 *
 */
session_start();
//parametri DB
include "../config/config.php";
//login
if (!isset($_SESSION["ID"])){
    header("Location: ../login.php");
}
?>
<!DOCTYPE html>
<div lang="it">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="author" content="Paolo Randone">
    <title>Calendario trasporti</title>

    <? require "../config/include/header.html";?>

    <script src="../config/js/gcal.js"></script>
    <script src="../config/js/it.js"></script>

    <script>
        $(document).ready(function () {
            var agendacal = $('#agendacal').fullCalendar({
                eventRender: function (event, element){
                    if ((event.stato) !== '1'){
                        element.addClass('confermato');
                    }else if ((event.start.format("HH:mm:ss")) === "06:00:00"){
                        element.addClass('mattino');
                    }else if
                    ((event.start.format("HH:mm:ss")) === "08:00:00"){
                        element.addClass('centrale');
                    }else if
                    ((event.start.format("HH:mm:ss")) === "01:00:00"){
                        element.addClass('giorno');
                    }
                    else {
                        element.addClass('pomeriggio');
                    }
                    return(['all', event.user_id].indexOf($("#modalFilterID option:selected").val())>=0)&&(['all', event.start.format("HH:mm:ss")].indexOf($("#modalFilterTime option:selected").val())>=0);
                },
                customButtons: {
                    refreshBTN: {
                        text: 'Aggiorna',
                        click: function(){location.reload();}
                    },
                    filterBTN: {
                        text: 'Filter',
                        click: function () {
                            $('#modal3').modal('show');
                            $("#filterButton").click(function () {
                                $('#modal3').modal('hide');
                                agendacal.fullCalendar('refetchEvents')
                            });
                            $("#resetButton").click(function () {
                                $('#modal3').modal('hide');
                                location.reload();
                            });
                        }
                    },
                },
                header: {
                    left: 'prev filterBTN,refreshBTN,today',
                    center: 'title',
                    right: 'basicWeek,month next',
                },
                validRange: function(nowDate) {
                    return {
                        start: nowDate.clone().subtract(1, 'years'),
                        end: nowDate.clone().add(1, 'months')
                    };
                },
                eventOrder: "event.id",
                //aspectRatio: 5,
                editable: true,
                selectable: false,
                displayEventEnd: false,
                eventDurationEditable: false,
                //eventOverlap: false,
                defaultView: 'month',
                themeSystem: 'bootstrap4',
                displayEventTime: false,
                googleCalendarApiKey: 'AIzaSyDUFn_ITtZMX10bHqcL0kVsaOKI0Sgg1yo',
                eventSources: [
                    {
                        // AGENDA STRAORDINARIO
                        url: 'loadmobilita.php',
                        type: 'POST',
                        data: {
                            stato: 'stato',
                            id: 'id',
                            user_id: 'user_id'
                        },
                    },
                    {
                        // festività nazionali
                        googleCalendarId: 'rpiguh13hptg6bq4imt5udgjpo@group.calendar.google.com',
                        color: 'red',
                        className: 'nolink',
                    }
                ],
                //FUNZIONE DISABILITATA
                eventClick:function(event, jsEvent){
                    jsEvent.preventDefault();

                }
            });
            var calendaruser = $('#calendaruser').fullCalendar({
                eventRender: function (event, element){
                    if ((event.stato) !== '1'){
                        element.addClass('confermato');
                    }else if ((event.start.format("HH:mm:ss")) === "06:00:00"){
                        element.addClass('mattino');
                    }else if
                    ((event.start.format("HH:mm:ss")) === "08:00:00"){
                        element.addClass('centrale');
                    }else if
                    ((event.start.format("HH:mm:ss")) === "01:00:00"){
                        element.addClass('giorno');
                    }
                    else {
                        element.addClass('pomeriggio');
                    }
                    return(['all', event.id].indexOf($("#modalFilterID option:selected").val())>=0)&&(['all', event.start.format("HH:mm:ss")].indexOf($("#modalFilterTime option:selected").val())>=0);
                },
                customButtons: {
                    refreshBTN: {
                        text: 'Aggiorna',
                        click: function(){location.reload();}
                    },
                    filterBTN: {
                        text: 'Filter',
                        click: function () {
                            $('#modal3').modal('show');
                            $("#filterButton").click(function () {
                                $('#modal3').modal('hide');
                                agendacal.fullCalendar('refetchEvents')
                            });
                            $("#resetButton").click(function () {
                                $('#modal3').modal('hide');
                                location.reload();
                            });
                        }
                    },
                },
                header: {
                    left: 'prev filterBTN,refreshBTN,today',
                    center: 'title',
                    right: 'basicWeek,month next',
                },
                eventOrder: "event.id",
                //aspectRatio: 3,
                editable: true,
                selectable: false,
                displayEventEnd: false,
                eventDurationEditable: false,
                //eventOverlap: false,
                defaultView: 'month',
                themeSystem: 'bootstrap4',
                displayEventTime: false,
                googleCalendarApiKey: 'AIzaSyDUFn_ITtZMX10bHqcL0kVsaOKI0Sgg1yo',
                eventSources: [
                    {
                        // CALENDARIO TRASPORTI
                        url: 'loadmobilita.php',
                        type: 'POST',
                        data: {
                            stato: 'stato',
                            id: 'id',
                            equipaggio: 'equipaggio',
                            partenza: 'partenza',
                            destinazione: 'destinazione',
                            AR: 'AR',
                            note: 'note',
                            id_mezzo: 'id_mezzo'
                        },
                    },
                    {
                        // festività nazionali
                        googleCalendarId: 'rpiguh13hptg6bq4imt5udgjpo@group.calendar.google.com',
                        color: 'red',
                        className: 'nolink',
                        rendering: 'background'

                    }
                ],
                dayClick: function(date) { //INSERISCI TRASPORTO
                    if(moment() <= date){
                        var day = date.format("YYYY-MM-DD");
                        $('#modal4').modal('show');
                        $('#addButton').off('click').on('click', function () {
                            $('#modal4').modal('hide');
                            var user_id = $("#user_id").val();
                            var title = $("#cognomenome").val();
                            if (($("#modalAddStart option:selected").val()) !== ""){
                                var start = day + " " + $("#modalAddStart option:selected").val();
                                var endStr = $.fullCalendar.moment(start);
                                endStr.add(1, 'hours');
                                var end =endStr.format("YYYY-MM-DD HH:mm:ss");
                                $.ajax({
                                    url: "insert.php",
                                    type: "POST",
                                    data: {title:title, start:start, end:end, user_id:user_id},
                                    success: function () {
                                        calendaruser.fullCalendar('refetchEvents');
                                        Swal.fire({text: "Trasporto inserito con successo", icon: "success", timer: 1000, button: false, closeOnClickOutside: false});
                                        setTimeout(function () {
                                                location.reload();
                                            }, 1001
                                        )
                                    }
                                });
                            }else{
                                alert("Seleziona un turno dall'elenco a discesa!")
                            }
                        });
                    }else{
                        Swal.fire({title: "ERRORE!", text:"Non è una macchina del tempo!", icon: "error", button:true, closeOnClickOutside: false});
                    }
                },
                eventClick:function(event, jsEvent){ //elimina disponibilità
                    jsEvent.preventDefault();
                    alert (event.equipaggio);
                    var title = $("#cognomenome").val();
                    //alert(event.start.format("YYYY-MM-DD"));
                    if((moment().format("YYYY-MM-DD")) < (event.start.format("YYYY-MM-DD"))){
                        Swal.fire({
                            text: "Sei sicuro di voler cancellare questa disponibilità?",
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
                                    var id = event.id;
                                    $.ajax({
                                        url:"script.php",
                                        type:"POST",
                                        data:{id:id},
                                        success:function(){
                                            calendaruser.fullCalendar('refetchEvents');
                                            Swal.fire({text:"Disponibilità eliminata con successo", icon: "success", timer: 1000, button:false, closeOnClickOutside: false});
                                            setTimeout(function () {
                                                    location.reload();
                                                },1001
                                            )
                                        }
                                    });
                                } else {
                                    Swal.fire({text:"Operazione annullata come richiesto!", timer: 1000, button:false, closeOnClickOutside: false});
                                }
                            })
                    }else{
                        calendaruser.fullCalendar('refetchEvents');
                        Swal.fire({title: "ERRORE!", text:"Non puoi eseguire questa operazione", icon: "error", button:true, closeOnClickOutside: false});
                    }
                },
            });
        });
    </script>

    <!-- NAVBAR -->
<div class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../index.php" style="color: #078f40">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php" style="color: #078f40">Mobilità</a></li>
            <li class="breadcrumb-item active" aria-current="page">Calendario trasporti</li>
        </ol>
    </nav>
</div>
<br>

<div class="container-fluid">
    <div id='calendaruser'</div>


<div align="center">Legenda: <span style="color: darkorange" >PROGRAMMATO</span>, <span style="color: royalblue" >CONFERMATO</span></div>

<!-- MODAL INSERIMENTO -->
<div id="modal4" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h6 class="modal-title" id="modal4Title">Inserisci trasporto</h6>
                </div>
                <div class="modal-body">
                    <label for="start_event">
                        Orario sul posto
                    </label>
                    <input type="time" class="form-control form-control-sm" id="start_event" name="start_event" value="<?=$modifica['start_event']?>">
                </div>
                <div class="modal-footer justify-content-center">
                    <div class="btn-group btn-group" role="group">
                        <button type="button" class="btn btn-outline-success btn-sm" id="addButton"><i class="far fa-check-circle"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL FILTRO-->
<div id="modal3" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h6 class="modal-title" id="modal3Title">Filtra per...</h6>
                </div>
                <div class="modal-body" align="center">
                    <div>Dipendente</div>
                    <select id="modalFilterID" name="modalFilterID" class="form-control form-control-sm" required>
                        <option value="all">Tutti</option>
                        <?
                        $selectfilter = $db->query("SELECT ID, cognome, nome FROM utenti WHERE livello='1' ORDER BY cognome");
                        while($ciclo = $selectfilter->fetch_array()){
                            echo "<option value=\"".$ciclo['ID']."\">".$ciclo['cognome'].' '.$ciclo['nome']."</option>";
                        }
                        ?>
                    </select>
                    <hr>
                    <div>Orario</div>
                    <select id="modalFilterTime" name="modalFilterTime" class="form-control form-control-sm" required>
                        <option value="all">Tutti</option>
                        <option value="06:00:00">Mattino</option>
                        <option value="08:00:00">Centrale</option>
                        <option value="13:00:00">Pomeriggio</option>
                    </select>

                </div>
                <div class="modal-footer justify-content-center">
                    <div class="btn-group btn-group" role="group">
                        <button type="button" class="btn btn-outline-warning btn-sm" id="resetButton"><i class="fas fa-reply"></i></button>
                        <button type="button" class="btn btn-outline-success btn-sm" id="filterButton"><i class="fas fa-filter"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL ASSEGNA TURNO-->
<div id="modal2" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h6 class="modal-title" id="modal2Title">Conferma straordinario</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" align="center">
                    <select id="modalAssigned" name="modalAssigned" class="form-control form-control-sm" required>
                        <option value="2">Assegna</option>
                        <option value="1">Annulla</option>
                    </select>
                </div>
                <div class="modal-footer justify-content-center">
                    <div class="btn-group btn-group" role="group">
                        <button type="button" class="btn btn-outline-success btn-sm" id="assignButton"><i class="far fa-check-circle"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<?php include('../config/include/footer.php'); ?>
