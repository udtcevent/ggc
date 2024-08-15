<?php
include('conn.php');
include('check.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');
$date = date('Y-m-d H:i:s');
?>

<div class="modal fade" id="modalFormEditCalendarEvents" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div id="loadContentModalEditCalendarEvents"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFormDeleteSummons" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div id="loadContentModalDeleteSummons"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFormAddNewUser" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div id="loadContentModalAddNewUser"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFormEditUser" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div id="loadContentModalEditUser"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFormDeleteUser" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div id="loadContentModalDeleteUser"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFormAddNewCourt" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div id="loadContentModalAddNewCourt"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFormAddNewPosition" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div id="loadContentModalAddNewPosition"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFormAddNewLevelPosition" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div id="loadContentModalAddNewLevelPosition"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFormAddMoreSummon" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div id="loadContentModalAddMoreSummon"></div>
        </div>
    </div>
</div>