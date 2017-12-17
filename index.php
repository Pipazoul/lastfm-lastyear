<?php include('header.html') ?>
    
    
    <div class="uk-grid">
        <div class="uk-width-1-3"></div>
        <div class="uk-width-1-3">

            <div class="uk-card-default">
                <div class="uk-card-header ">
                    <h3 class="uk-card-title">Enter your Lastfm pseudo</h3>
                </div>
                <div class="uk-card-body ">
                    <form action="fetch.php" method="post">
                    <input class=" uk-textarea" type="text" name="pseudo" id="pseudo">
                    <button class="uk-button uk-button-default uk-align-center">Let's go !</button>
                </form>
                </div>
            </div>
        </div>
        <div class="uk-width-1-3"></div>
    </div>
</body>

</html>
