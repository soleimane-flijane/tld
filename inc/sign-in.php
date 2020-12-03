<?php if(!defined("APP_NAME")) exit(); ?>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 form-container" id="sign-in-form-container">
            <h2 class="sign-in-heading text-center">Connexion</h2>
            <form method="post" id="signInForm">
                <div class="form-group">
                    <label for="username">Adresse Email ou Pseudo</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Adresse Email ou Pseudo" required />
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Mot de passe" required />
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember_me">Ce Souvenir
                    </label>
                </div>
                <div class="alert alert-danger alert-errors"></div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">Connexion</button><hr />
                <p>Si vous n'avez pas de compte, vous pouvez vous inscrire via le lien ci-dessous.</p>
                <a href="#" id="sign-up-link">Inscription</a>
                <input type="hidden" name="_token" class="token-field" value="<?php echo isset($_SESSION["token"]) ? $_SESSION["token"] : ""; ?>" />
            </form>
        </div>
    </div>
</div>