async function login(loginData) {
    try {
        const response = await fetch("/auth/login", {
            method: "POST",
            body: JSON.stringify(loginData),
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
            },
        });
        if (response.ok) {
            const data = await response.json();
            if (data.data.user.superuser) {
                window.location.href = "/wordle_cake/admin"
            } else {
                window.location.href = "/"
            }
        }
        if (response.status === 401) {
            showError("Invalid credentials");
        }
    } catch (error) {
        console.error(error);
    }
}

const form = document.querySelector(".login-form");
form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const email = document.querySelector("input[name=email]").value;
    const password = document.querySelector("input[name=password]").value;
    await login({ email, password });
});

function showError(message) {
    const p = document.createElement("p");
    p.textContent = message;
    p.style.color = "red";
    form.appendChild(p);
}
