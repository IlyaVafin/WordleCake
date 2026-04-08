async function createCategory(categoryData) {
    try {
        const response = await fetch("http://localhost:8000/api/category", {
            method: "POST",
            headers: {
                Accept: "application/json",
            },
            body: categoryData,
            credentials: "include",
        });
        const data = await response.json();
        console.log(data);
    } catch (error) {
        console.error(error);
    }
}

const form = document.querySelector(".create-category-form");

form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    await createCategory(formData);
});
