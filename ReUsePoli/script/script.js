const form = document.querySelector("form");

form.addEventListener("submit", function(event) {

    const password = document.getElementById("password").value;
    const ulangipassword = document.getElementById("ulangipassword").value;
    const pesan = document.getElementById("pesanPassword");

    if (password !== ulangipassword) {
        event.preventDefault();

        pesan.textContent = "Password Nya Beda Tuh Mas";
    }
});