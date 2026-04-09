const API_URL = "http://localhost:8000/api/category"
const form = document.querySelector(".create-category-form");
const nextButton = document.querySelector(".next-button")
const prevButton = document.querySelector(".prev-button")
const categoryList = document.querySelector(".categories")
const searchInput = document.querySelector(".search-category")
let page = 1;
let lastPage;
let paginatedData = []
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
    } catch (error) {
        console.error(error);
    }
}

async function getCategories(pageArg) {
    try {
        const response = await fetch(`${API_URL}?page=${pageArg}`)
        if (response.ok) {
            const data = await response.json()
            console.log(data);
            return data
        }
    } catch (error) {
        console.error(error)
    }
}

async function deleteCategory(id) {
    try {
        const response = await fetch(`${API_URL}`)
        const data = await response.json()
        return data
    } catch (error) {
        console.error(error)
    }
}

async function getPaginatedData(page) {
    const response = await getCategories(page);
    return response.categories.data
}

function renderCategories(data) {
    while (categoryList.firstChild) {
        categoryList.removeChild(categoryList.firstChild)
    }
    for (const category of data) {
        const li = document.createElement("li")
        const h3 = document.createElement("h3")
        const paragraphName = document.createElement("p")
        const paragraphId = document.createElement("p")
        const a = document.createElement("a")
        const image = document.createElement("img")
        image.alt = `category-${category.name}-photo`
        image.src = `http://localhost:8000/storage/${category.image}`
        image.onload = () => {
            image.width = 300
            image.height = 300
        }
        const buttonsContainer = createCategoryButtons(category.id)
        h3.textContent = category.name
        paragraphName.textContent = category.description
        paragraphId.textContent = `ID: ${category.id}`
        li.appendChild(image)
        li.appendChild(h3)
        li.appendChild(paragraphName)
        li.appendChild(paragraphId)
        li.appendChild(buttonsContainer)
        categoryList.appendChild(li)
    }
}


function createCategoryButtons(categoryId) {
    const buttonsContainer = document.createElement("div")
    buttonsContainer.dataset.categoryId = categoryId
    const deleteButton = document.createElement("button")
    deleteButton.classList.add("delete-button")
    deleteButton.textContent = "Delete category"
    const editButton = document.createElement("button")
    editButton.classList.add("edit-button")
    editButton.textContent = "Edit category"
    buttonsContainer.appendChild(deleteButton)
    buttonsContainer.appendChild(editButton)
    return buttonsContainer
}

function searchCategory(query) {
    const newCategories = paginatedData.filter(cat => cat.name.toLowerCase().includes(query.toLowerCase()))
    renderCategories(newCategories)
}


searchInput.addEventListener("input", (e) => {
    searchCategory(e.target.value)
})
nextButton.addEventListener('click', async () => {
    console.log(`Page: ${page}\nLastPage: ${lastPage}`);
    if (lastPage === page) return;
    page += 1
    paginatedData = await getPaginatedData(page)
    renderCategories(paginatedData)
})

prevButton.addEventListener('click', async () => {
    if (page === 1) return
    page -= 1
    paginatedData = await getPaginatedData(page)
    renderCategories(paginatedData)
})

form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    await createCategory(formData);
});
document.addEventListener("DOMContentLoaded", async () => {
    const data = await getCategories(page);
    paginatedData = data.categories.data

    await renderCategories(paginatedData)
    lastPage = data.categories.last_page
})



