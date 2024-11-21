/**document.addEventListener("DOMContentLoaded", () => {
   Elementos principais
  const trashCards = document.querySelectorAll(".trash-card");
  const loginModal = document.getElementById("loginModal");
  const trashHistory = document.querySelector(".trash-history");

   Exibe o modal de login ao carregar a página
  if (loginModal && trashHistory) {
      loginModal.style.display = "flex";
      trashHistory.style.display = "none"; Esconde o histórico até o login
  }

   Função para verificar a senha
  window.checkPassword = function() {
      const passwordInput = document.getElementById("password");
      const loginError = document.getElementById("loginError");
      const correctPassword = "farofada666"; Altere para a senha desejada

      if (passwordInput.value === correctPassword) {
          loginModal.style.display = "none";
          trashHistory.style.display = "flex";
      } else {
          loginError.textContent = "Senha incorreta. Tente novamente.";
          passwordInput.value = "";
      }
  };

   Função para atualizar status das lixeiras e data de edição
  function updateStatus(card) {
      const statusIcon = card.querySelector(".status-icon");
      const editDate = card.querySelector(".edit-date");
      const isFull = Math.random() < 0.5; Simulação de status aleatório

       Atualiza status e exibe o ícone correspondente
      if (isFull) {
          statusIcon.classList.remove("empty");
          statusIcon.classList.add("full");
          statusIcon.textContent = "🔴";
      } else {
          statusIcon.classList.remove("full");
          statusIcon.classList.add("empty");
          statusIcon.textContent = "🟢";
      }

      / Atualiza a data de edição
      const now = new Date();
      editDate.textContent = now.toLocaleDateString("pt-BR") + " " + now.toLocaleTimeString("pt-BR");
  }

   Atualiza cada cartão de lixeira ao carregar a página
  trashCards.forEach(card => updateStatus(card));

   Dados de usuários para autenticação
  const users = {
      senac: { password: "senac123", lastLogin: null, profilePic: "senac.jpg" },
      puc_rs: { password: "puc123", lastLogin: null, profilePic: "puc.jpg" },
      ufrgs: { password: "ufrgs123", lastLogin: null, profilePic: "ufrgs.jpg" },
      unisinos: { password: "unisinos123", lastLogin: null, profilePic: "unisinos.jpg" },
      admin: { password: "farofada666", lastLogin: null, profilePic: "admin.jpg" }
  };

 Função de verificação de login
  window.checkLogin = function() {
      const username = document.getElementById("username").value.toLowerCase();
      const password = document.getElementById("password").value;
      const user = users[username];

      if (user && user.password === password) {
          user.lastLogin = new Date().toLocaleString("pt-BR");
          displayUserProfile(user);
      } else {
          alert("Usuário ou senha incorretos.");
      }
  };

 Exibe perfil do usuário após login bem-sucedido
  function displayUserProfile(user) {
      const profileContainer = document.getElementById("profile");
      profileContainer.innerHTML = `
          <img src="${user.profilePic}" alt="Profile Picture">
          <p>Último login: ${user.lastLogin}</p>
      `;
  }
}); */