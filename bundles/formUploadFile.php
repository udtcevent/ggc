
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
      ระบบบริหารจัดการปฏิทินงานและการแจ้งเตือนผ่านไลน์ - UDTC Announcement
    </title>
    <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/48ae6a34dc.js" crossorigin="anonymous"></script>
    <!-- Latest compiled and minified CSS -->
    <script>
      $(document).ready(() => {
        if (sessionStorage.length < 1) {
          $("#loadForm").load("bundles/loadDataTableSummons.php");
          $("#modalForm").load("bundles/modalForm.php?curPage=0");
        } else {
          const data = JSON.parse(sessionStorage.sess);
          window.location.replace("main.html");
        }
      });
    </script>
    
    <!-- <link href='fullcalendar-4.4.2/packages/core/main.css' rel='stylesheet' />
    <link href='fullcalendar-4.4.2/packages/daygrid/main.css' rel='stylesheet' />
    <script src='fullcalendar-4.4.2/packages/core/main.js'></script>
    <script src='fullcalendar-4.4.2/packages/daygrid/main.js'></script>

    <script src='fullcalendar-4.4.2/packages/core/main.js'></script>
    <script src='fullcalendar-4.4.2/packages/daygrid/main.js'></script>

    <script src='fullcalendar-4.4.2/packages/core/locales/th.js'></script>
    <script src='fullcalendar-4.4.2/packages/timegrid/main.js'></script>
    <script src='fullcalendar-4.4.2/packages/interaction/main.js'></script>
    <script src='fullcalendar-4.4.2/packages/list/main.js'></script>
    <style type="text/css">
      #calendar{
        width: 800px;margin: auto;
      }
      </style>     -->
  </head>

  <body>
    <div class="col-12">
      <div id="loadForm" style="width: 100%"></div>
      <div id="modalForm" style="width: 100%"></div>
      <div class="row">
        <div class="col-12" style="text-align: center;">
          <iframe src="https://calendar.google.com/calendar/embed?height=800&wkst=1&ctz=Asia%2FBangkok&bgcolor=%23ffffff&title=%E0%B8%A3%E0%B8%B0%E0%B8%9A%E0%B8%9A%E0%B9%81%E0%B8%88%E0%B9%89%E0%B8%87%E0%B9%80%E0%B8%95%E0%B8%B7%E0%B8%AD%E0%B8%99%E0%B8%81%E0%B8%B4%E0%B8%88%E0%B8%81%E0%B8%A3%E0%B8%A3%E0%B8%A1%E0%B8%A8%E0%B8%B2%E0%B8%A5%E0%B8%88%E0%B8%B1%E0%B8%87%E0%B8%AB%E0%B8%A7%E0%B8%B1%E0%B8%94%E0%B8%AD%E0%B8%B8%E0%B8%94%E0%B8%A3%E0%B8%98%E0%B8%B2%E0%B8%99%E0%B8%B5&src=dWR0Y2V2ZW50QGdtYWlsLmNvbQ&color=%23039BE5" style="border:solid 1px #777" width="1280" height="800" frameborder="0" scrolling="no"></iframe>
          <!-- <div id='calendar' style="width: 100%; display: inline-block;"></div> -->
        </div>
        <script type="text/javascript" src="js/fullcalendar.js"></script> 
    </div>

    <script>
      ViewSummons = (id) => {
        $("#loadContentModalEditDataSummons").html("");
        $("#loadContentModalEditDataSummons").load(
          "bundles/modalEditDataSummons.php?id=" + id
        );
        $("#modalFormEditDataSummons").modal("show");
      };

      ReviewTypeSearch = () => {
        $("#reviewTypeSeach").html("");
        if ($("#typeSearchDataSummons").val() < 3) {
          $("#reviewTypeSeach").append(
            `<div class="form-floating"><input type="text" id="searchSummons" class="form-control" style="width:100%;" placeholder="ระบุคำค้นหา..." /><label for="floatingInputGrid" style="color:#212121;">ระบุคำค้นหา...</label></div>`
          );
        } else {
          $("#reviewTypeSeach").load("bundles/dataCourt.php");
        }
      };

      SearchSummon = () => {
        const searchSummons = $("#searchSummons").val();
        if (searchSummons == "") {
          alert("กรุณากรอกข้อมูลก่อนดำเนินการค้นหา...");
        } else {
          const typeSearchDataSummons = $("select#typeSearchDataSummons").val();
          const dataCheck = {
            searchSummons:
              typeSearchDataSummons < 3
                ? searchSummons
                : $("select#court").val(),
            typeSearchDataSummons: typeSearchDataSummons,
          };

          $.ajax({
            type: "POST",
            url: "bundles/checkHasData.php",
            data: dataCheck,
            dataType: "json",
            success: (result) => {
              result.auth == false
                ? setTimeout(() => {
                    alert("ไม่พบข้อมูลคำค้นหาที่ท่านต้องการ...");
                  }, 0)
                : setTimeout(() => {
                    $("#fetchDataSearch").load("bundles/searchDataSummons.php?dataSearch=" + (typeSearchDataSummons < 3
                          ? $("#searchSummons").val()
                          : $("select#court").val()) +
                        "&typeSearchDataSummons=" +
                        typeSearchDataSummons
                    );
                  }, 0);
            },
          });
        }
      };

      ShowCalendar = () => {
        window.open('https://calendar.google.com/calendar/embed?height=800&wkst=1&ctz=Asia%2FBangkok&bgcolor=%23ffffff&title=%E0%B8%A3%E0%B8%B0%E0%B8%9A%E0%B8%9A%E0%B9%81%E0%B8%88%E0%B9%89%E0%B8%87%E0%B9%80%E0%B8%95%E0%B8%B7%E0%B8%AD%E0%B8%99%E0%B8%81%E0%B8%B4%E0%B8%88%E0%B8%81%E0%B8%A3%E0%B8%A3%E0%B8%A1%E0%B8%A8%E0%B8%B2%E0%B8%A5%E0%B8%88%E0%B8%B1%E0%B8%87%E0%B8%AB%E0%B8%A7%E0%B8%B1%E0%B8%94%E0%B8%AD%E0%B8%B8%E0%B8%94%E0%B8%A3%E0%B8%98%E0%B8%B2%E0%B8%99%E0%B8%B5&src=dWR0Y2V2ZW50QGdtYWlsLmNvbQ&color=%23039BE5', '_blank');
      }
    </script>
  </body>
</html>
