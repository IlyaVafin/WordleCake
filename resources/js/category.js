const API_URL = "http://localhost:8000/api/category";
const form = document.querySelector(".create-category-form");
const nextButton = document.querySelector(".next-button");
const prevButton = document.querySelector(".prev-button");
const categoryList = document.querySelector(".categories");
const searchInput = document.querySelector(".search-category");
let page = 1;
let lastPage;
let paginatedData = [];
async function createCategory(categoryData) {
    try {
        const response = await fetch(API_URL, {
            method: "POST",
            headers: {
                Accept: "application/json",
            },
            body: categoryData,
            credentials: "include",
        });
        const data = await response.json();
        console.log(data);
        return data;
    } catch (error) {
        console.error(error);
    }
}

async function getCategories(pageArg) {
    try {
        const response = await fetch(`${API_URL}?page=${pageArg}`);
        if (response.ok) {
            const data = await response.json();
            console.log(data);
            return data;
        }
    } catch (error) {
        console.error(error);
    }
}

async function deleteCategory(id) {
    try {
        const response = await fetch(`${API_URL}/${id}`, {
            method: "DELETE",
            headers: {
                Accept: "application/json",
            },
        });
        const data = await response.json();
        console.log(data);

        return data;
    } catch (error) {
        console.error(error);
    }
}

async function getPaginatedData(page) {
    const response = await getCategories(page);
    return response.categories.data;
}

function renderCategories(data) {
    while (categoryList.firstChild) {
        categoryList.removeChild(categoryList.firstChild);
    }
    for (const category of data) {
        const li = document.createElement("li");
        const h3 = document.createElement("h3");
        const paragraphName = document.createElement("p");
        const paragraphId = document.createElement("p");
        const a = document.createElement("a");
        a.href = `/category/${category.id}`;
        const image = document.createElement("img");
        image.alt = `category-${category.name}-photo`;
        image.src = `http://localhost:8000/storage/${category.image}`;
        image.onload = () => {
            image.width = 300;
            image.height = 300;
        };
        const buttonsContainer = createCategoryButtons(category.id);
        h3.textContent = category.name;
        paragraphName.textContent = category.description;
        paragraphId.textContent = `ID: ${category.id}`;

        a.appendChild(image);
        a.appendChild(h3);
        a.appendChild(paragraphName);
        a.appendChild(paragraphId);
        li.appendChild(a);
        li.appendChild(buttonsContainer);
        categoryList.appendChild(li);
    }
}

function createCategoryButtons(categoryId) {
    const buttonsContainer = document.createElement("div");
    buttonsContainer.className = "flex gap-2";
    buttonsContainer.dataset.categoryId = categoryId;
    const deleteButton = document.createElement("button");
    deleteButton.className = "bg-black text-white pt-1 pb-1 pr-4 pl-4";
    deleteButton.textContent = "Delete category";
    const editButton = document.createElement("button");
    editButton.className = "bg-black text-white pt-1 pb-1 pr-4 pl-4";
    editButton.textContent = "Edit category";
    buttonsContainer.appendChild(deleteButton);
    buttonsContainer.appendChild(editButton);
    deleteButton.addEventListener("click", async () => {
        const id = buttonsContainer.dataset.categoryId;
        paginatedData = paginatedData.filter((cat) => String(cat.id) !== id);
        renderCategories(paginatedData);
        await deleteCategory(id);
    });
    return buttonsContainer;
}

function searchCategory(query) {
    const newCategories = paginatedData.filter((cat) =>
        cat.name.toLowerCase().includes(query.toLowerCase()),
    );
    renderCategories(newCategories);
}

searchInput.addEventListener("input", (e) => {
    searchCategory(e.target.value);
});
nextButton.addEventListener("click", async () => {
    console.log(`Page: ${page}\nLastPage: ${lastPage}`);
    if (lastPage === page) return;
    page += 1;
    paginatedData = await getPaginatedData(page);
    renderCategories(paginatedData);
});

prevButton.addEventListener("click", async () => {
    if (page === 1) return;
    page -= 1;
    paginatedData = await getPaginatedData(page);
    renderCategories(paginatedData);
});

form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    const newCategory = await createCategory(formData);
    paginatedData.push(newCategory);
    renderCategories(paginatedData);
});
document.addEventListener("DOMContentLoaded", async () => {
    const data = await getCategories(page);
    paginatedData = data.categories.data;

    await renderCategories(paginatedData);
    lastPage = data.categories.last_page;
});
