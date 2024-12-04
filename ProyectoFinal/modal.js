const openModalButton = document.getElementById('openModal');
const closeModalButton = document.getElementById('closeModal');
const modal = document.getElementById('modal');
const editProfileButton = document.getElementById('editProfileButton');
const editProfileForm = document.getElementById('editProfileForm');
const profileInfo = document.getElementById('profile-info');

openModalButton.addEventListener('click', () => {
    modal.style.display = 'flex'; 
});

closeModalButton.addEventListener('click', () => {
    modal.style.display = 'none'; 
});

window.addEventListener('click', (event) => {
    if (event.target === modal) {
        modal.style.display = 'none'; 
    }
});

editProfileButton.addEventListener('click', () => {
    profileInfo.style.display = 'none'; 
    editProfileForm.style.display = 'block'; 
});