const container = document.querySelector('.container')
const scrollUp = document.querySelector('.up')

const observer = new IntersectionObserver((entries) => {
    for (const entry of entries) {
        if (entry.isIntersecting) {
            scrollUp.classList.add('display');
        } else {
            scrollUp.classList.remove('display');
        }
    }
});

observer.observe(container)

scrollUp.onclick = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    })
}