var GoogleAuth;
// var SCOPE = 'https://www.google.com/m8/feeds/';
var API_KEY='AIzaSyCNXa4WD9Z_RNpKw2nMc9VvWfHAJzVNkKA';
var Client_ID='845569391753-fqg13jpt5bi1i6vu4q008kncmjffn4p2.apps.googleusercontent.com';

var DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/gmail/v1/rest"];
var SCOPE = 'https://www.googleapis.com/auth/plus.login';


var append_emails={};

function handleClientLoad() {
    $('#sign-in-or-out-button').attr("disabled","disabled");

    gapi.load('client:auth2', initClient);
}

function initClient() {

    gapi.client.init({
        'apiKey': API_KEY,
        'clientId': Client_ID,
        'scope': SCOPE
    }).then(function () {

        GoogleAuth = gapi.auth2.getAuthInstance();

        GoogleAuth.isSignedIn.listen(updateSigninStatus);

        setSigninStatus();


        $('#sign-in-or-out-button').removeAttr("disabled");

        $('#sign-in-or-out-button').click(function() {
            handleAuthClick();

            return false;
        });

    });

}

function google_logout(reload) {

    console.log("google logout");

    if(typeof (reload)==undefined){
        reload=true;
    }

    if (GoogleAuth.isSignedIn.get()) {

        gapi.client.init({
            'apiKey': API_KEY,
            'clientId': Client_ID,
            'scope': SCOPE,
            'discoveryDocs': DISCOVERY_DOCS
        }).then(function () {
            GoogleAuth = gapi.auth2.getAuthInstance();
            GoogleAuth.isSignedIn.listen(updateSigninStatus);

            GoogleAuth.disconnect();

            if(reload){
                location.reload();
            }

        });

    }
}

function handleAuthClick() {
    if (GoogleAuth.isSignedIn.get()) {
        GoogleAuth.signOut();
    } else {
        GoogleAuth.signIn();
    }
}

function setSigninStatus() {
    var user = GoogleAuth.currentUser.get();

    // var isAuthorized = user.hasGrantedScopes(SCOPE);
    var isAuthorized = true;

    console.log(user.w3);

    if (isAuthorized&&user.w3!=undefined) {

        var obj={};
        obj.email=user.w3.U3;
        obj.provider_id=user.El;
        obj.username=user.w3.ig;


        if(obj.email.length>0){

            google_logout(false);

            location.href=$(".base_url").val()+"/google_login?" +
                "email="+obj.email+"" +
                "&provider_id="+obj.provider_id+"" +
                "&username="+obj.username+"" +
                "";
        }


    }
}

function updateSigninStatus(isSignedIn) {
    setSigninStatus();
}

$(function(){
    handleClientLoad();

    $("body").on("click",".logout_from_google_account",function () {

        if (GoogleAuth.isSignedIn.get()) {
            gapi.client.init({
                'apiKey': API_KEY,
                'clientId': Client_ID,
                'scope': SCOPE
            }).then(function () {
                GoogleAuth = gapi.auth2.getAuthInstance();
                GoogleAuth.isSignedIn.listen(updateSigninStatus);

                GoogleAuth.disconnect();

                GoogleAuth.signOut().then(function () {
                    $('#sign-in-or-out-button').html('Gmail');
                    location.reload();
                });

            });

        }

        $(this).hide();
    });


});