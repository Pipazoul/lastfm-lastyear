<?php include('header.html') 
?>

<?php
        //Checks if has pseudo cookie
        if (isset($_COOKIE["pseudo"])) {
            $pseudo = $_COOKIE["pseudo"];
        }
        else {
            $pseudo = 'Username';
        }
    
?>
    
    
    <div class="uk-grid">
        <div class="uk-width-1-3"></div>
        <div class="uk-width-1-3">

            <div class="uk-card-default">
                <div class="uk-card-header ">
                    <h3 class="uk-card-title">Enter your Lastfm pseudo</h3>
                </div>
                <div class="uk-card-body ">
                    <form action="fetch.php" method="post">
                    <input class=" uk-textarea" type="text" name="pseudo" id="pseudo" value="<?php echo $pseudo; ?>">
                    <p>How many years back ?</p>
                    <select name="years" id="years">
                        <option value="1" >1</option>
                        <option value="2" >2</option>
                        <option value="3" >3</option>
                        <option value="4" >4</option>
                        <option value="5" >5</option>
                    </select>
                    year
                    
                    <button id="submit" class="uk-button uk-button-default uk-align-center">Let's go !</button>
                </form>
                </div>
            </div>
        </div>
        <div class="uk-width-1-3"></div>
    </div>
</body>

</html>
