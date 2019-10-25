<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Information -->
    <meta charset="utf-8">
    <title>
        <?php echo app()->config['app_name'] ?? 'Favorite Links'; ?>
    </title>


    <!-- Style sheets-->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="/public/css/app.css?v=1.1" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <script>
        window.FL = <?php echo json_encode([
            'user' => $user->withOutHidden(),
            'title' => app()->config['app_name'] ?? 'Favorite Links',
        ]) ?>
    </script>

    <style>
        a {
            cursor: pointer;
        }

        .hidden {
            display: none
        }
    </style>
</head>
<body>

<div id="app">

    <div class="container mb-5">
        <div class="d-flex align-items-center py-4 header">
            <a href="/">
                <img src="/public/logo.png" alt="" class="logo">
            </a>

            <h4 class="mb-0 ml-3">
                <?php echo app()->config['app_name'] ?? 'Favorite Links'; ?>
            </h4>

            <h4 class="mb-0 ml-3">
                <a href="/logout" class="btn btn-success btn-xs">
                    Logout
                </a>
            </h4>

        </div>

        <div class="row mt-4">
            <div class="col-2 sidebar">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <router-link active-class="active" to="/links" class="nav-link d-flex align-items-center pt-0">
                            <i class="fas fa-link"></i>
                            <span>Links</span>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link active-class="active" to="/profile" class="nav-link d-flex align-items-center pt-0">
                            <i class="fas fa-user"></i>
                            <span>Profile</span>
                        </router-link>
                    </li>

                    <li class="nav-item" v-if="user.rol == 'admin'">
                        <router-link active-class="active" to="/users" class="nav-link d-flex align-items-center pt-0">
                            <i class="fas fa-users"></i>
                            <span>Users</span>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link active-class="active" to="/todos" class="nav-link d-flex align-items-center pt-0">
                            <i class="fas fa-tasks"></i>
                            <span>To-Do</span>
                        </router-link>
                    </li>
                </ul>
            </div>

            <div class="col-10">
                <router-view></router-view>
            </div>
        </div>
    </div>
</div>

<script src="/public/js/app.js?v=4"></script>
</body>
</html>
