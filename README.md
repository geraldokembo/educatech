
EducaTech 📚
Plataforma web de gestão educacional e e-learning desenvolvida como MVP.

🚀 Sobre o Projeto
O **EducaTech** é uma solução digital focada em descentralizar e otimizar o acesso à educação. A plataforma conecta alunos a conteúdos formativos de qualidade, proporcionando ao mesmo tempo um painel de controlo robusto para professores e administradores gerirem cursos e matrículas.

---

## 🛠️ Tecnologias Utilizadas
* **Backend:** PHP (Gestão de sessões e lógica de negócio)
* **Base de Dados:** MySQL / MySQLi (Relacional)
* **Frontend:** HTML5, CSS3 (Flexbox & CSS Grid), JavaScript (Manipulação de DOM e timeouts)
* **Ambiente de Desenvolvimento:** XAMPP (Apache / PHP / phpMyAdmin)

---
## ✨ Funcionalidades Principais
* **Controlo de Acessos Baseado em Papéis (RBAC):**
  * Diferenciação automática entre *Alunos* e *Professores/Administradores* através de um sistema de papéis e sessões seguras.
* **Autenticação e Segurança de Sessão:**
  * Gestão de sessões (`session_start()`), validação de credenciais e guardas de rota rigorosas.
* **Experiência do Utilizador (UX/UI) Fluida:**
  * Interface moderna em modo escuro na área de aulas, com navegação intuitiva por playlists e acompanhamento de progresso em tempo real.
* **Exploração Inteligente de Cursos:**
  * Listagem dinâmica baseada em consultas SQL otimizadas (`NOT IN` subqueries) para ocultar cursos onde o aluno já se encontra matriculado.
* **🔒 Segurança Avançada e Proteção de Conteúdo (Anti-Pirataria):**
  * **Marca d'Água Dinâmica por Sessão:** Injeção automatizada via PHP de uma grelha translúcida com o nome e o ID único do aluno sobre a página de aulas, dissuasão forte contra capturas e partilhas não autorizadas.
  * **Restrições de Contexto:** Bloqueio de clique direito, atalhos de gravação/impressão (`F12`, `Ctrl+P`, `Ctrl+S`, `Ctrl+U`) e desativação da seleção de texto.
* **Interação Pedagógica:**
  * Sistema integrado de envio de dúvidas por aula e respetiva resposta por parte do professor.
---

## 📂 Estrutura de Ficheiros (Principais)
```text
/educatech
│
├── conexao.php             # Conexão centralizada com a base de dados MySQL
├── logar.php               # Processamento de login e interface unificada de autenticação
├── cadastrar.php           # Registo de novos utilizadores com validação de papéis
├── dashboard.php           # Painel principal do aluno
├── dashboard_professor.php # Painel de controlo restrito para docentes
├── explorar.php            # Vitrine de cursos disponíveis com filtro de matrículas
├── inscrever.php           # Lógica de processamento de inscrições
├── meus_cursos.php         # Gestão de cursos matriculados pelo aluno
└── assets/                 # Folhas de estilo, imagens e recursos estáticos

```

---

## ⚙️ Como Executar o Projeto Localmente

1. **Pré-requisitos:**
* Certifica-te de que tens o **XAMPP** (ou ambiente PHP/MySQL similar) instalado no teu computador.


2. **Clonar ou Colocar o Repositório:**
* Coloca a pasta do projeto dentro do diretório raiz do teu servidor local (ex: `htdocs` no XAMPP).


3. **Configurar a Base de Dados:**
* Abre o **phpMyAdmin** (`http://localhost/phpmyadmin`).
* Cria uma base de dados (ex: `educatech_db`).
* Importa as tabelas necessárias (`usuarios`, `cursos`, `inscricoes`).


4. **Configurar a Conexão:**
* Abre o ficheiro `conexao.php` e confirma se os teus parâmetros de acesso ao MySQL estão corretos (por defeito: `localhost`, `root`, sem password).


5. **Aceder à Aplicação:**
* Abre o navegador e acede a:
`http://localhost/educatech/logar.php`



---

## 💡 Próximos Passos do MVP

* [ ] Implementação de upload de conteúdos em vídeo pelos professores.
* [ ] Sistema de recuperação de palavra-passe.
* [ ] Emissão de certificados de conclusão de curso.

---

## 🛡️ Licença

Desenvolvido por **Geraldo Kembo** . Todos os direitos reservados.

