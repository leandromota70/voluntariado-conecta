#  Voluntariado Conecta  
Sistema de Gestão de Vagas de Voluntariado para ONGs

---

## 📘 Sobre o Projeto
O **Voluntariado Conecta** é uma plataforma desenvolvida para facilitar a conexão entre **ONGs** e **voluntários**, permitindo que organizações cadastrem vagas, gerenciem oportunidades e acompanhem inscrições de maneira simples e eficiente.

Este sistema foi criado como projeto acadêmico no curso **Análise e Desenvolvimento de Sistemas — SENAC**, aplicando práticas modernas de desenvolvimento, banco de dados e usabilidade.

---

## 👥 Integrantes do Grupo
- Alexandre Almice Pereira  
- Arthur Barros da Silva  
- Breno De Lima Pereira  
- Danilo Guimaraes Ferreira  
- Francisco Wallas da Silva  
- Gabriel Lessa Nascimento  
- Leandro da Costa Silva  
- Tatyelle Costa  

---

## Prova de Conceito

O projeto evoluiu de uma versão inicial em HTML e CSS estático para uma aplicação dinâmica.  
Nesta etapa, implementamos:
- Envio de formulários com PHP
- Integração com banco de dados via SQL

Essa prova de conceito valida a comunicação entre front-end e back-end, garantindo o registro e armazenamento dos dados enviados pelos usuários.

## 🚀 Funcionalidades

### 🔐 Área da ONG
- Login com autenticação e controle de sessão  
- Dashboard administrativo  
- Logout  
- Navegação protegida para usuários autenticados  

### 📄 Gestão de Vagas
- Cadastro de novas vagas  
- Listagem de vagas da ONG logada  
- Armazenamento no MySQL  
- Mensagens de sucesso e erro  
- Validação dos campos obrigatórios  

### 🌐 Área Pública
- Catálogo de vagas  
- Detalhes da vaga  
- Formulário para inscrições  

---

## 🛠 Tecnologias Utilizadas

### **Frontend**
- HTML5  
- TailwindCSS  
- AOS.js  
- Feather Icons  
- JavaScript

### **Backend**
- PHP 8  
- Sessions (controle de login)

### **Banco de Dados**
- MySQL (phpMyAdmin)

### **Ambiente**
- XAMPP (Apache + MySQL)

---

## 🗂 Estrutura do Projeto

voluntariado-conecta/
│
├── index.html
├── catalogo.html
├── formulario-inscricao.html
├── detalhe-vaga.html
│
├── ong-login.php
├── dashboard-ong.php
├── criar-vaga.php
├── minhas-vagas.php
├── inscritos.php
│
├── conexao.php
├── logout.php
│
└── README.md


---

## 💾 Banco de Dados

### 📌 Nome do Banco
**voluntariado_conecta**

### 📌 Tabelas usadas

#### 🟣 ongs
| Campo | Tipo | Descrição |
|--------|------|-----------|
| id_ong | INT (PK) | Identificador |
| nome   | VARCHAR | Nome da ONG |
| email  | VARCHAR | Login |
| senha  | VARCHAR | Senha |

#### 🟣 vagas
| Campo | Tipo | Descrição |
|--------|------|-----------|
| id_vaga | INT (PK) | Identificador |
| id_ong | INT (FK) | ONG criadora |
| titulo | VARCHAR | Título da vaga |
| area | VARCHAR | Área de atuação |
| tipo_atividade | VARCHAR | Presencial / Remoto / Híbrido |
| cidade | VARCHAR | Cidade |
| estado | VARCHAR(2) | UF |
| carga_horaria_semana | VARCHAR | Carga horária |
| dias_horarios | VARCHAR | Dias e horários |
| descricao | TEXT | Descrição da vaga |
| status | VARCHAR | ativa / inativa |
| criado_em | TIMESTAMP | Data criada |
| atualizado_em | TIMESTAMP | Atualizada |

---

## 🗄️ Como Configurar o Banco de Dados

1. Abra o **XAMPP** e inicie:
   - **Apache**
   - **MySQL**
2. Clique em **Admin** no MySQL para abrir o **phpMyAdmin**
3. Clique em **Novo** e crie o banco:

voluntariado_conecta

4. Clique na aba **Importar**
5. Selecione o arquivo:

voluntariado_conecta.sql

6. Clique em **Executar**

Após a importação, o banco estará pronto.

---

### 🔑 Conta de acesso para testes (Dashboard ONG)

E-mail: contato@ongeducar.org

Senha: 123456


---

## ▶ Como Executar o Projeto

1. Copie o projeto para:

C:/xampp/htdocs/


2. Inicie **Apache** e **MySQL** no XAMPP  
3. Importe o banco de dados conforme instruções acima  
4. Acesse no navegador:

http://localhost/voluntariado-conecta/

5. Acessar área da ONG:

http://localhost/voluntariado-conecta/ong-login.php


---

## 🖼 Capturas de Tela (adicionar depois)
- [ ] Página inicial  
- [ ] Login  
- [ ] Dashboard  
- [ ] Criar vaga  
- [ ] Minhas vagas  
- [ ] Banco de dados  

---

## 📚 Objetivo Educacional
Este projeto reforça os seguintes conteúdos:
- Modelagem de banco de dados  
- Autenticação e sessões  
- CRUD parcial  
- Arquitetura cliente-servidor  
- Uso de frameworks CSS (Tailwind)  
- Integração frontend + backend  

---

## 👨‍🏫 Professor Responsável
*(Adicionar nome do professor se necessário)*

---

## 📄 Licença
Projeto acadêmico — uso educacional.
