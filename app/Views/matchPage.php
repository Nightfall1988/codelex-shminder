<html>
    <head></head>
    <body>
        <h1>You have a match!</h1>

        <div>
            {% for profile in post %}
            <p>{{ profile[0].name }} MATCHES WITH {{ profile[1].name }}</p>
            {% endfor %}
        </div>
    </body>
</html>