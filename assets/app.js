/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

$("#new_edit_user").on('submit', function(){
    console.log("test")
    if($("#user_password").val() != $("#verifpass").val()) {
        alert("Les deux mots de passe saisies sont différents");
        alert("Merci de renouveler l'opération");
        return false;
    }
})
