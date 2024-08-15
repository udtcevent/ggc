<table class="table table-striped" style="background-color: #212121; color: #fff">
    <thead>
        <tr>
            <th style="width: 10%; font-weight: bold; font-size: 16px">
                <a type="button" class="btn btn-danger btn-lg" style="width: 100%" href="index.html"><?php echo "<< back"; ?></a>
            </th>
            <th style="width: 90%"></th>
        </tr>
    </thead>
</table>
<section class="ftco-section">
    <div class="container" style="padding-top: 5%">
        <div class="row justify-content-center" style="background-color: #616161; color:#fff;">
            <div class="col-md-6 text-center mb-5">
                <h2 class="heading-section" style="padding-top: 20px;">
                ระบบบริหารจัดการปฏิทินงานและการแจ้งเตือนผ่านไลน์<br />[ UDTC Announcement ]
                </h2>
            </div>
        </div>

        <div class="row justify-content-center" style="background-color: #616161; color:#fff; margin-top: -50px">
            <div class="col-md-6 col-lg-5">
                <div class="login-wrap p-4 p-md-5">
                    <form action="#" class="login-form">
                        <div class="form-group">
                            <input type="text" id="userName" class="form-control rounded-left" placeholder="Username" required />
                        </div>
                        <br />
                        <div class="form-group d-flex">
                            <input type="password" id="passWord" class="form-control rounded-left" placeholder="Password" required />
                        </div>
                        <br />
                        <button type="button" class="btn btn-primary btn-lg" style="width: 100%" onclick="Login();">
                            <i class="fa fa-sign-in"></i> Login
                        </button>
                        <!-- <div class="g-signin2" data-onsuccess="onSignIn"></div> -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>