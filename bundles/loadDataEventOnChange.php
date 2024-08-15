<table class="table table-striped">
    <thead>
        <tr style="background-color: #000; color:#fff; text-align:center;">
            <th>#</th>
            <th>หัวข้อกิจกรรม</th>
            <th>รายละเอียด</th>
            <th>วันเดือนปีกิจกรรม</th>
            <th>เวลาเริ่มและสิ้นสุดกิจกรรม</th>
            <th>ประเภทกิจกรรม</th>
            <th>ผู้นำเข้าข้อมูล</th>
            <th>วันที่นำเข้าข้อมูล</th>
            <?php
            if ($rUser["_authentication_level"] == 1) { ?>
                <th style="width:25%;">จัดการ</th>
            <?php } else { ?>
                <th>ไฟล์</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        $qCalendarEvent = mysqli_query($conn, "SELECT
                                                                ce.id AS id,
                                                                ce._event_title AS eventTitle,
                                                                ce._event_detail AS eventDetails,
                                                                ce._event_type_id AS eventTypeID,
                                                                et._event_type_name AS eventTypeName,
                                                                ce._event_startdate AS eventStartDate,
                                                                ce._event_starttime AS eventStartTime,
                                                                ce._event_endtime AS eventEndTime,
                                                                ce._event_createdate AS eventCreateDate,
                                                                ce._user_create AS userCreate
                                                            FROM _calendar_event AS ce
                                                            LEFt JOIN _event_type AS et ON ce._event_type_id = et.id
                                                            ORDER BY ce.id DESC LIMIT 10");
        $cCalendarEvent = mysqli_num_rows($qCalendarEvent);
        if ($cCalendarEvent < 1) { ?>
            <tr>
                <td colspan="9"><button type="button" class="btn btn-default btn-block" style="text-align: center; width: 100%; font-weight:bold;" disabled>ไม่พบข้อมูลรายการกิจกรรมที่นำเข้าในวันนี้..</button></td>
            </tr>
            <?php } else {
            while ($rCalendarEvent = mysqli_fetch_array($qCalendarEvent)) {
                //USER INSERT DATA EVENTS
                $qInsertDataEvent = mysqli_query($conn, "SELECT 
                                                                        CONCAT(fn._front_name, u._fname, ' ', u._lname) AS fullName
                                                                    FROM _user AS u
                                                                    LEFT JOIN _front_name AS fn ON u._front_name = fn.id
                                                                    WHERE u.id = '" . $rCalendarEvent["userCreate"] . "'");
                $rInsertDataEvent = mysqli_fetch_assoc($qInsertDataEvent);
                mysqli_free_result($qInsertDataEvent);
            ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><span class="badge-black"><?php echo $rCalendarEvent["eventTitle"]; ?></span></td>
                    <td><?php echo $rCalendarEvent["eventDetails"]; ?></td>
                    <td><?php echo substr($rCalendarEvent["eventStartDate"], 8, 2) . ' ' . Check_Month(substr($rCalendarEvent["eventStartDate"], 5, 2)) . ' ' . (substr($rCalendarEvent["eventStartDate"], 0, 4) + 543); ?></td>
                    <td><?php echo '<span class="badge-green">' . substr($rCalendarEvent["eventStartTime"], 0, 5) . '</span> - <span class="badge-red">' . substr($rCalendarEvent["eventEndTime"], 0, 5) . '</span>'; ?></td>
                    <td><?php echo $rCalendarEvent["eventTypeName"]; ?></td>
                    <td><?php echo $rInsertDataEvent["fullName"]; ?></td>
                    <td><?php echo substr($rCalendarEvent["eventCreateDate"], 8, 2) . ' ' . Check_Month(substr($rCalendarEvent["eventCreateDate"], 5, 2)) . ' ' . (substr($rCalendarEvent["eventCreateDate"], 0, 4) + 543); ?></td>
                    <td>
                        <!-- <a class="btn <?php echo ($rUser["_authentication_level"] == 1 ? "btn-success" : "btn-danger"); ?>" href="<?php echo $rDataFileUpload["_file_path"] . $rDataFileUpload["_file_encode_name"]; ?>" target="_blank"><i class="fa fa-file-pdf-o"></i> หมาย</a> -->
                        <button type="button" class="btn btn-primary" onclick="EditCalendarEvents(<?php echo $rCalendarEvent['id']; ?>);"><i class="fa fa-edit"></i> แก้ไข</button>
                        <button type="button" class="btn btn-danger" onclick="DeleteCalendarEvents(<?php echo $rCalendarEvent['id'] . ',' . $rDataFileUpload['id']; ?>);"><i class="fa fa-trash"></i> ลบ</button>
                    </td>
                </tr>
        <?php $i++;
            }
        } ?>
    </tbody>
</table>