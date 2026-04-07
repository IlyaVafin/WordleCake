async function send(message) {
    try {
        const response = await fetch("/", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            credentials: 'include',
            body: JSON.stringify({message}),
        });
        console.log(await response.json());
    } catch (error) {
        console.error(error);
    }
}

document.querySelector("form").addEventListener("submit", async (e) => {
    e.preventDefault();
    console.log("lol");
    
    const input = document.querySelector("input[name='message']").value;
    await send(input);
});
