<h2 class="w3-text-brown">Belépés / Regisztráció</h2>

<p>
    A bejelentkezés után elérhetővé válnak a felhasználókhoz kötött funkciók,
    például az üzenetek megtekintése és a képfeltöltés.
</p>

<div class="w3-row-padding">

    <div class="w3-half">
        <div class="w3-card w3-light-grey w3-padding w3-round w3-margin-bottom">
            <h3 class="w3-text-brown">Belépés</h3>

            <form action="belep" method="post">
                <label>Felhasználónév</label>
                <input
                    class="w3-input w3-border w3-round"
                    type="text"
                    name="felhasznalo"
                    placeholder="felhasználó"
                >

                <label>Jelszó</label>
                <input
                    class="w3-input w3-border w3-round"
                    type="password"
                    name="jelszo"
                    placeholder="jelszó"
                >

                <button
                    class="w3-button w3-orange w3-text-white w3-round w3-margin-top"
                    type="submit"
                    name="belepes"
                >
                    Belépés
                </button>
            </form>
        </div>
    </div>

    <div class="w3-half">
        <div class="w3-card w3-light-grey w3-padding w3-round w3-margin-bottom">
            <h3 class="w3-text-brown">Regisztráció</h3>

            <form action="regisztral" method="post">
                <label>Vezetéknév</label>
                <input
                    class="w3-input w3-border w3-round"
                    type="text"
                    name="vezeteknev"
                    placeholder="vezetéknév"
                >

                <label>Utónév</label>
                <input
                    class="w3-input w3-border w3-round"
                    type="text"
                    name="utonev"
                    placeholder="utónév"
                >

                <label>Felhasználónév</label>
                <input
                    class="w3-input w3-border w3-round"
                    type="text"
                    name="felhasznalo"
                    placeholder="felhasználói név"
                >

                <label>Jelszó</label>
                <input
                    class="w3-input w3-border w3-round"
                    type="password"
                    name="jelszo"
                    placeholder="jelszó"
                >

                <button
                    class="w3-button w3-orange w3-text-white w3-round w3-margin-top"
                    type="submit"
                    name="regisztracio"
                >
                    Regisztráció
                </button>
            </form>
        </div>
    </div>

</div>