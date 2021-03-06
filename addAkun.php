<?php
require_once "conn.php";

if (!$func->isLoggedIn()) {
    header("Location: ./");
}

if (isset($e)) {
    $err = basename($_SERVER['SCRIPT_FILENAME']);
    header("Location: errorPage.php?page=" . $err);
}

$usr = $func->fetch_user_info("username");
$userid = $func->fetch_user_info('id');

if (isset($_POST['saveAkun'])) {
    $email = $_POST['useremail'];
    $provider = $_POST['provider'];
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
    }
    if ($func->save_akun($userid, $email, $password, $provider)) {
        $success = TRUE;
    } else {
        $error = $func->getLastError();
    }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DataAkun - ds.raihankhalid.my.id</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"><!--  -->
    <link rel="stylesheet" href="assets/css/style.css"><!-- Custom CSS for this page  -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>

<body>
    <nav class="navbar">
        <a class="navbar-brand text-white mt-1" href="./">
            <h4>TokyoLights | Home</h4>
        </a>
        <a href="logout.php" class="form-inline my-2 my-lg-0 btn btn-danger">Logout</a>
    </nav>
    <div class="container-xl">
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-sm-5">
                            <h2>DataAkun - <b><?= $usr; ?></b></h2>
                            <br>
                            <?php if (isset($success)) : ?>
                                <div class="alert alert-success my-auto mx-auto" role="alert"><strong>Success adding account <b><?php echo isset($email) ? $email : '' ?></b></strong></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-7">
                            <button type="button" data-toggle="modal" data-target="#formAdd" class="btn btn-primary"><i class="material-icons">&#xE147;</i> <span>Add New Akun</span></button>
                            <a href="home.php" type="button" class="btn btn-primary"><i class="material-icons">&#xe5c4;</i> <span>Back to DataShop</span></a>

                            <!-- START FORM MODAL TAMBAH DATA -->
                            <div class="modal fade" id="formAdd" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-md" role="document">
                                    <div class="modal-content text-white bg-dark">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">New Akun</h5>
                                            <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST">
                                                <div class="form-group">
                                                    <input type="text" name="useremail" class="form-control inpt" placeholder="akun@example.com">
                                                </div>
                                                <div class="form-group">
                                                    <select class="form-control inpt mb-1" name="provider">
                                                        <option disabled selected>Provider</option>
                                                        <option>Digital Ocean</option>
                                                        <option>Vultr</option>
                                                        <option>Linode</option>
                                                        <option>AWS</option>
                                                        <option>Azure</option>
                                                        <option>IBM Cloud</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <details class="mt-3 mb-1">
                                                        <summary>Note</summary>
                                                        <p></p>
                                                        <p class="anti-select">Password bersifat Opsional, Password akan di enkripsi sebelum disimpan.</p>
                                                    </details>
                                                    <input type="text" name="password" class="form-control inpt" placeholder="Password (Optional)">
                                                </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                            <button type="submit" name="saveAkun" class="btn btn-primary">Save</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- END FORM MODAL TAMBAH DATA -->


                        </div>
                    </div>
                </div>
                <table class="table text-white">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                            <th>Password 
                                <br>
                                <a type="button" class="text-primary">Show Password</a>
                            </th>
                            <th>Provider</th>
                            <th>Date Added</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $func->list_user_akun($userid);
                        ?>
                    </tbody>
                </table>
                <div class="clearfix">
                    <div class="hint-text text-white">Showing <b>5</b> out of <b>25</b> entries</div>
                    <ul class="pagination">
                        <li class="page-item disabled"><a href="#">Previous</a></li>
                        <li class="page-item"><a href="#" class="page-link">1</a></li>
                        <li class="page-item"><a href="#" class="page-link">2</a></li>
                        <li class="page-item active"><a href="#" class="page-link">3</a></li>
                        <li class="page-item"><a href="#" class="page-link">4</a></li>
                        <li class="page-item"><a href="#" class="page-link">5</a></li>
                        <li class="page-item"><a href="#" class="page-link">Next</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 2000);
    </script>
</body>

</html>