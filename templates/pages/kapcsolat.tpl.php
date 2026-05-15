<h2>Kapcsolat</h2>

<p>Ügyvezető: <strong>Valaki Valaki</strong></p>
<p>E-mail: <strong>valaki.az@valaki.hu</strong></p>

<form id="contactForm" class="w3-card w3-padding" method="post" action="logicals/contactapi.php">

    <label>Tárgy</label>
    <input class="w3-input w3-border" type="text" name="subject" id="subject">

    <label>Üzenet</label>
    <textarea class="w3-input w3-border" name="message" id="message" rows="5"></textarea>

    <button class="w3-button w3-orange" type="submit">Küldés</button>
    <label><?php echo isset($_GET['success']) ? 'Üzenet elküldve!' : ''; ?></label>
</form>

<h3>Térkép</h3>

<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2726.3375296155727!2d19.66695091525771!3d46.89607994478184!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4743da7a6c479e1d%3A0xc8292b3f6dc69e7f!2sPallasz+Ath%C3%A9n%C3%A9+Egyetem+GAMF+Kar!5e0!3m2!1shu!2shu!4v1475753185783"
    width="600"
    height="350"
    style="border:0; max-width:100%;"
    allowfullscreen>
</iframe>

<br>

<a target="_blank" href="https://www.google.hu/maps/place/Pallasz+Ath%C3%A9n%C3%A9+Egyetem+GAMF+Kar/@46.8960799,19.6669509,17z/data=!3m1!4b1!4m5!3m4!1s0x4743da7a6c479e1d:0xc8292b3f6dc69e7f!8m2!3d46.8960763!4d19.6691396?hl=hu">
    Nagyobb térkép
</a>

<script>
    document.getElementById('contactForm').addEventListener('submit', function(event) {
        var name = document.getElementById('name').value;
        var email = document.getElementById('email').value;
        var subject = document.getElementById('subject').value;
        var message = document.getElementById('message').value;

        if (name.length < 3) {
            alert('A név legalább 3 karakter legyen.');
            event.preventDefault();
            return;
        }

        if (email === '' || !email.includes('@') || !email.includes('.')) {
            alert('Hibás e-mail cím.');
            event.preventDefault();
            return;
        }

        if (subject.length < 3) {
            alert('A tárgy legalább 3 karakter legyen.');
            event.preventDefault();
            return;
        }

        if (message.length < 10) {
            alert('Az üzenet legalább 10 karakter legyen.');
            event.preventDefault();
        }
    });
</script>