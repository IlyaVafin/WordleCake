async function register(formData) {
    try {
        const response = await fetch("http://localhost:8000/registration", {
            method: "POST",
            headers: {
                Accept: "application/json",
            },
            body: formData,
        });
        if (response.ok) {
            const data = await response.json();
            localStorage.setItem("token", data.credentials.token)
            window.location.href = "/"
        }
    } catch (error) {
        console.error(error);
    }
}

const form = document.querySelector(".form");
const submitter = document.querySelector(".submit-register");
form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    await register(formData);
});
