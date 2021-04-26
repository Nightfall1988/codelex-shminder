<html>
    <head></head>
    <link rel='stylesheet' href='public\css\style.css'>
    <body class='bg-gray-200 mr-14 ml-14 content-center mr-20 ml-20'>
    {% for person in post %}
        <h1>Welcome to Shminder, {{ person.name }}!</h1>

    {% endfor %}
    <form method="POST" action="rate">
        <div>
            <div id='image' class="flex justify-center">
            {% for person in post %}
                {% set profile = person.suggestedlikes.userList[0] %}
                <div class="flex justify-center p-2"><img class=" w-96 h-96" src="{{profile.images[0]}}"></img></div>
            </div>
            <div class="flex justify-center">
                <div class='mr-50 ml-50'>
                <button name='rate' value="like/{{profile.ID}}"><img src="public/appimages/heart.png" width="50px" height= "50px"></button>
                </div>
                <div id='data' class="text-3xl flex justify-center"><p>{{ profile.name }}</p>
                </div>
                <div class='mr-50 ml-50'>
                <button name='rate' value="dislike/{{profile.ID}}"><img src="public/appimages/cross.png" width="50px" height= "50px"></button>

                <!-- <button style="background-image: url(public/appimages/cross.png);  height:200px; width:200px;" type='image' class='h-10 w-10' name='rate' value="dislike/{{profile.ID}}"></button> -->
                </div>
            </div>
            {% endfor %}
        </div>
        <div id='users'>
        </div>
    </form>
        <form action="upload" method="post" enctype="multipart/form-data">
            Select image to upload:
            <input type="file" name="fileToUpload" id="fileToUpload"></input>
            <input type="submit" value="Upload Image" name="submit"></input>
        </form>
        <script>
            function nextSlide(){
                let activeSlide = document.querySelector('.slide.translate-x-0');
                activeSlide.classList.remove('translate-x-0');
                activeSlide.classList.add('-translate-x-full');
                
                let nextSlide = activeSlide.nextElementSibling;
                nextSlide.classList.remove('translate-x-full');
                nextSlide.classList.add('translate-x-0');
            }
        </script>
    </body>
</html>