<html>
    <head></head>
    <link rel='stylesheet' href='public\css\style.css'>
 
    <body class="bg-gray-200 mr-14 ml-14 content-center mr-20 ml-20">
        <h1>Welcome to Shminder!</h1>
        <div id='forms'>
            <div id='registration'>
                <form  method='POST'>
                    <label for='username'> Username: </label>
                    <input type="text" name='username'></input>
                    <label for='password'> Password: </label>
                    <input type="password" name='password'></input>
                    <label for='sex'> Sex: </label>
                    <select name='sex'>
                        <option>M</option>
                        <option>F</option>
                    </select>
                    <label for='age'> Age: </label>
                    <select name='age'>
                    {% for i in 18..100 %}
                        <option> {{ i }} </option>
                    {% endfor %}
                    </select>
                    <br>
                    <button formaction="register">REGISTER</button>
                </form>
            </div>
            <div id='login'>
                <form  method='POST'>
                    <label for='username'> Username: </label>
                    <input type="text" name='username'></input>
                    <label for='password'> Password: </label>
                    <input type="password" name='password'></input>
                    <br>
                    <button formaction="login">LOGIN</button>
                </form>
            </div>
        </div>
    </body>
</html>