<h1 align="center">PlanetGame (back-end)</h1>

<ul>
<li><a href="#english-documentation">English documentation</a></li>
<li><a href="#documentação-em-português">Documentação em português</a></li>
</ul>

<hr>
<p><h1 id="english-documentation">English Documentation</h1></p>
<h2>📜 Table of content</h2>

<!--ts-->
<ul>
    <li><a href="#about-the-project">About the project</a></li>
    <li><a href="#live-demo">Live demo</a></li>
    <li><a href="#features">Features</a></li>
    <li><a href="#front-end">Front-end</a></li>
    <ul>
        <li><a href="#layout">Layout</a></li>
        <li><a href="#technologies-front">Technologies</a></li>
    </ul>
    <li><a href="#back-end">Back-end</a></li>
    <ul>
        <li><a href="#technologies-back">Technologies</a></li>
    </ul>
    <li><a href="#database">Database</a></li>
    <li><a href="#how-to-set-up">How to set up</a></li>
    <li><a href="#inspirations">Inspirations</a></li>
    <li><a href="#author">Author</a></li>
</ul>
<!--te-->

<blockquote>
    <p>
        <b>
            🤚 This repository is the server-side of PlanetGame, if you are interested on client-side, check <a href="https://github.com/livramatheus/planetgame_front">this repository</a>!
        </b>
    </p>
</blockquote>

<h2 id="about-the-project">💻 About the project</h2>

<p>
    PlanetGame is a game database prototype website that indexes information about games, genres and publishers. It was developed with <b>React,  PHP and MySQL</b>.
</p>

<p>
    The main goal with this project was to release a full-stack application focusing in applying a solid development workflow, consistent code quality, a good organization and also learning new things along the way.
</p>

<p>
    In this project I tried for the fist time <b>GitHub Projects</b> with Automated Kanban, and I gotta say that the experience was incredible. Usually my personal projects development process turns out to be really chaotic, and by adopting this tool, the development workflow became way cleaner and faster.
</p>

<p>
    Another crucial mechanism that I adopted for the first time on this project was having <b>three separate environments</b>: development, test and production. This was easily achivable by pushing code to distinct git branches and turning on auto deploy on Netlify and Heroku.
</p>

<p>
    Lastly, this was the first project that I implemented a <b>JWT authentication</b>. I did the token's creation from the ground up via PHP, and, to handle it on the front-end I used a third party package. I gotta admit that the token implementation on the back-end may need some security improvements, but, for now is enough.
</p>

<p>
    You can find more detailed info about the project in the next sections.
</p>

<h2 id="live-demo">🌐 Live demo</h2>

<em>The project is unavailable because no viable free hosting solution has been found.</em>

<h2 id="features">🔎 Features</h2>

<p>
    The main functionality of this application is the possibility of users suggesting a game to be listed on the website through a form, without any kind of login or authentication. However, the games sent by users will remain hidden up to the point an admin reviews and approves it.
</p>

<p>
    In a more detailed way, the following are the core features of PlanetGame:
</p>

<ul>
    <li>Game genres listing</li>
    <li>Game publishers listing</li>
    <li>Games listing</li>
    <li>Publisher details</li>
    <li>Game details</li>
    <li>Game suggesting (new game)</li>
    <li>Admin login</li>
    <li>Game approval</li>
    <li>Game denial (delete)</li>
</ul>

<h2 id="front-end">Front-end</h2>

<p>
    The client-side of <b>PlanetGame</b> was built with React library. The key technical points to be noted throughout development of the front-end was the use of <b>custom hooks</b> and a clean component organization.
</p>

<p>
    The main and secondary pages are presented with random banners to maintain a refreshing experience for the user. As this process envolved a few heavy images to be loaded, I <a href="https://developers.google.com/speed/webp">converted those banners from .png to .webp</a> to improve website performance. The results was incredible: I measured the first paint of home page before and after the conversion: the loading time decreased from <b>1.4 seconds to 0.7 seconds</b>.
</p>

<p>
    To improve the user experience, in the sections where data is fetched asynchronously  from the back-end, Skeletons from MUI package was used as a placeholder until the data is completely loaded.
</p>

<p>
    The UI, being entirely <b>responsive</b> was developed from the ground up using HTML and CSS. Talking about responsiveness, a hamburger menu was also developed from scratch, only using AOS and hamburger-react libraries for its animations. 
</p>

<h3 id="layout">📐 Layout</h3>

<h4>Mobile</h4>
<div style="display: flex; gap: 0.2rem">
    <img alt="Mobile 1" title="Main Page" src="./github/1_m.webp" height="360"/>
    <img alt="Mobile 2" title="Hamburger Menu" src="./github/2_m.webp" height="360"/>
    <img alt="Mobile 3" title="Data Table" src="./github/3_m.webp" height="360"/>
    <img alt="Mobile 4" title="New Game Form" src="./github/4_m.webp" height="360"/>
</div>

<h4>Desktop</h4>

<div style="display: flex; flex-direction: column; gap: 0.2rem">

<div style="display: flex; gap: 0.2rem">
    <img alt="Desktop 1" title="Main Page" src="./github/1_d.webp" height="240"/>
    <img alt="Desktop 2" title="Data table" src="./github/2_d.webp" height="240"/>
</div>

<div style="display: flex; gap: 0.2rem">
    <img alt="Desktop 3" title="New Game Form" src="./github/3_d.webp" height="240"/>
    <img alt="Desktop 4" title="Admin Login Form" src="./github/4_d.webp" height="240"/>
</div>

<div style="display: flex; gap: 0.2rem">
    <img alt="Desktop 5" title="Admin Panel" src="./github/5_d.webp" height="240"/>
    <img alt="Desktop 6" title="Admin Actions" src="./github/6_d.webp" height="240"/>
</div>

</div>

<h3 id="technologies-front">🔨 Technologies</h3>

<p>The following technologies were used to build PlanetGame front-end:</p>
<ul>
    <li><a href="https://www.npmjs.com/package/@mui/material">@mui/material</a></li>
    <li><a href="https://www.npmjs.com/package/@date-io/date-fns">@date-io/date-fns</a></li>
    <li><a href="https://www.npmjs.com/package/aos">aos</a></li>
    <li><a href="https://www.npmjs.com/package/axios">axios</a></li>
    <li><a href="https://www.npmjs.com/package/disable-scroll">disable-scroll</a></li>
    <li><a href="https://www.npmjs.com/package/hamburger-react">hamburger-react</a></li>
    <li><a href="https://www.npmjs.com/package/jwt-decode">jwt-decode</a></li>
    <li><a href="https://www.npmjs.com/package/react-helmet">react-helmet</a></li>
    <li><a href="https://www.npmjs.com/package/react-router-dom">react-router-dom</a></li>
</ul>

<h2 id="back-end">Back-end</h2>

<p>
    PlanetGame's back-end was developed with <b>vanilla PHP</b>, behaving as an API: by expecting requests and returning JSON responses.
</p>

<p>
    The routing system of this app was developed from scratch, providing a reliable and consistent communication between front and back end.
    Another interesting feature that was developed from scratch was the <b>error logging system</b> in pair with custom Exception classes.
</p>

<p>
    Lastly, an additional noteworthy point is the back-end was consistently backed by <b>unit tests</b>, to maintain a solid code quality throughout the entire development timespan.
</p>

<h3 id="technologies-back">🔨 Technologies</h3>

<p>The following technologies were used to build PlanetGame back-end:</p>
<ul>
    <li><a href="https://packagist.org/packages/mpratt/relativetime">mpratt/relativetime</a> - For converting dates to sentences like "1 year ago"</li>
    <li><a href="https://packagist.org/packages/mofodojodino/profanity-filter">mofodojodino/profanity-filter</a> - For filtering out bad words from forms</li>
    <li><a href="https://packagist.org/packages/phpunit/phpunit">phpunit/phpunit</a></li>
</ul>

<h2 id="database">Database</h2>

<p>PlanetGame's database of choice was <a href="https://www.mysql.com">MySql</a>. Check out a brief description of the tables:</p>
<ul>
    <li><strong>tb_admin:</strong> stores admins;</li>
    <li><strong>tb_game:</strong> store games. It is related to genres and publisher;</li>
    <li><strong>tb_genre:</strong> store game genres;</li>
    <li><strong>tb_publisher:</strong> stores game publishers</li>
</ul>

<h3 id="how-to-set-up">❓ How to set up</h3>

<p>If you wish to fork this project you'll need to set up the following environment variables:</p>

<h4 id="client-side-">Client-side:</h4>
<ul>
    <li>
        <strong>REACT_APP_API_URL</strong> (Server URL in order to send requests)
    </li>
    <li>
        <strong>REACT_APP_PUBLIC_URL</strong> (Front-end URL)
    </li>
</ul>

<h4 id="server-side-">Server-side:</h4>
<ul>
    <li>
        <strong>ADMIN_SALT</strong> (Salt for managing admin's passwords)
    </li>
    <li>
        <strong>CLEARDB_DATABASE_URL</strong> (URL for database connection)
    </li>
    <li>
        <strong>CLIENT_URL</strong> (Front-end url for CORS purposes)
    </li>
    <li>
        <strong>JWT_TOKEN_KEY</strong> (JWT secret for managing tokens)
    </li>
</ul>

<h3 id="inspirations">😁 Inspirations</h3>

<p>PlanetGame was inspired by the following projects on Figma:</p>
<ul>
    <li><a href="https://www.figma.com/community/file/871334426251486294">GWP TeamFight Tactics</a> (inspired PlanetGame color scheme)</li>
    <li><a href="https://www.figma.com/community/file/814078062940878144">LOL - 404 page for Weekly Warm-Up</a> (inspired PlanetGame UI)</li>
</ul>

<h3 id="author">👩‍🦲 Author</h3>

<p>Full stack developed by <strong>Matheus do Livramento</strong>.</p>

<p><a href="https://github.com/livramatheus">GitHub</a> | <a href="https://www.linkedin.com/in/livramatheus">LinkedIn</a> | <a href="https://www.livramento.dev/">Website</a></p>

<hr />

<h1 id="documentação-em-português">Documentação em português</h1>
<h2>📜 Tabela de conteúdo</h2>

<ul>
    <li><a href="#about-the-project-br">Sobre o projeto</a></li>
    <li><a href="#live-demo-br">Live demo</a></li>
    <li><a href="#features-br">Funcionalidades</a></li>
    <li><a href="#front-end-br">Front-end</a></li>
    <ul>
        <li><a href="#layout-br">Layout</a></li>
        <li><a href="#technologies-front-br">Tecnologias</a></li>
    </ul>
    <li><a href="#back-end-br">Back-end</a></li>
    <ul>
        <li><a href="#technologies-back-br">Technologies</a></li>
    </ul>
    <li><a href="#database-br">Banco de dados</a></li>
    <li><a href="#how-to-set-up-br">Como configurar</a></li>
    <li><a href="#inspirations-br">Inspirações</a></li>
    <li><a href="#autor-br">Autor</a></li>
</ul>

<blockquote>
<p><strong>🤚 🤚 Este repositório é do lado do servidor do PlanetGame, se você estiver interessado no lado do cliente, verifique <a href="https://github.com/livramatheus/planetgame_front">este repositório</a>!</strong></p>
</blockquote>

<h2 id="about-the-project-br">💻 Sobre o projeto</h2>

<p>
    PlanetGame é um site protótipo de banco de dados de jogos que indexa informações sobre jogos, gêneros e editoras. Foi desenvolvido com <b>React, PHP e MySQL</b>.
</p>

<p>
    O principal objetivo com este projeto era lançar uma aplicação full-stack com foco em aplicar um fluxo de trabalho de desenvolvimento sólido, qualidade de código consistente, uma boa organização e também aprender coisas novas ao longo do caminho.
</p>

<p>
    Neste projeto eu utilizei pela primeira vez <b>GitHub Projects</b> com Automated Kanban, e devo dizer que a experiência foi incrível. Normalmente meu processo de desenvolvimento de projetos pessoais acaba sendo muito caótico, e ao adotar essa ferramenta, o fluxo de trabalho de desenvolvimento ficou muito mais limpo e rápido.
</p>

<p>
    Outro mecanismo crucial que adotei pela primeira vez neste projeto foi ter <b>três ambientes separados</b>: desenvolvimento, teste e produção. Isso foi facilmente alcançável enviando código para <em>branches</em> distintas do git e ativando o <em>auto deploy</em> no Netlify e no Heroku.
</p>

<p>
    Por fim, este foi o primeiro projeto em que implementei uma <b>autenticação JWT</b>. Eu fiz a criação do token do zero via PHP e, para lidar com isso no front-end, usei um pacote de terceiros. Devo admitir que a implementação do token no back-end pode precisar de algumas melhorias de segurança, mas por enquanto é suficiente.
</p>

<p>
    Você pode encontrar informações mais detalhadas sobre o projeto nas próximas seções.
</p>

<h2 id="live-demo-br">🌐 Live demo</h2>

<em>O projeto não está disponível porque não foi encontrada nenhuma solução gratuita viável para hospedagem.</em>

<h2 id="features-br">🔎 Funcionalidades</h2>

<p>
     A principal funcionalidade deste aplicativo é a possibilidade de os usuários sugerirem que um jogo seja listado no site por meio de um formulário, sem nenhum tipo de login ou autenticação. No entanto, os jogos enviados pelos usuários permanecerão ocultos até o momento em que um administrador os revise e os aprove.
</p>

<p>
     De uma forma mais detalhada, a seguir estão os principais recursos do PlanetGame:
</p>

<ul>
     <li>Listagem de gêneros de jogos</li>
     <li>Listagem de publicadoras de jogos</li>
     <li>Listagem de jogos</li>
     <li>Detalhamento de editor</li>
     <li>Detalhamento de jogo</li>
     <li>Sugestão de jogo (novo jogo)</li>
     <li>Login do administrador</li>
     <li>Aprovação do jogo</li>
     <li>Negação de jogo (excluir)</li>
</ul>

<h2 id="front-end-br">Front-end</h2>

<p>
    O lado cliente do <b>PlanetGame</b> foi construído com a biblioteca React. Os principais pontos técnicos a serem observados durante o desenvolvimento do front-end foi o uso de <em><b>custom hooks</b></em> e uma organização limpa de componentes.
</p>

<p>
    As páginas principal e secundárias são apresentadas com <em>banners</em> aleatórios para manter uma experiência pouco enjoativa para o usuário. Como esse processo envolveu o carregamento de algumas imagens pesadas, <a href="https://developers.google.com/speed/webp">converti esses banners de .png para .webp</a> para melhorar o desempenho do site . Os resultados foram incríveis: medi o <em>first paint</em> da página inicial antes e depois da conversão: o tempo de carregamento médio diminuiu de <b>1,4 segundos para 0,7 segundos</b>.
</p>

<p>
    Para melhorar a experiência do usuário, nas seções em que os dados são buscados de forma assíncrona do back-end, o pacote Skeletons from MUI foi usado como um <em>placeholder</em> até que os dados sejam completamente carregados.
</p>

<p>
    A interface do usuário, sendo totalmente <b>responsiva</b>, foi desenvolvida do zero usando HTML e CSS. Falando em responsividade, um <em>hamburger menu</em> também foi desenvolvido do zero, usando apenas AOS e bibliotecas hamburger-react para suas animações.
</p>

<h3 id="layout-br">📐 Layout</h3>

<h4>Mobile</h4>
<div style="display: flex; gap: 0.2rem">
    <img alt="Mobile 1" title="Main Page" src="./github/1_m.webp" height="360"/>
    <img alt="Mobile 2" title="Hamburger Menu" src="./github/2_m.webp" height="360"/>
    <img alt="Mobile 3" title="Data Table" src="./github/3_m.webp" height="360"/>
    <img alt="Mobile 4" title="New Game Form" src="./github/4_m.webp" height="360"/>
</div>

<h4>Desktop</h4>

<div style="display: flex; flex-direction: column; gap: 0.2rem">

<div style="display: flex; gap: 0.2rem">
    <img alt="Desktop 1" title="Main Page" src="./github/1_d.webp" height="240"/>
    <img alt="Desktop 2" title="Data table" src="./github/2_d.webp" height="240"/>
</div>

<div style="display: flex; gap: 0.2rem">
    <img alt="Desktop 3" title="New Game Form" src="./github/3_d.webp" height="240"/>
    <img alt="Desktop 4" title="Admin Login Form" src="./github/4_d.webp" height="240"/>
</div>

<div style="display: flex; gap: 0.2rem">
    <img alt="Desktop 5" title="Admin Panel" src="./github/5_d.webp" height="240"/>
    <img alt="Desktop 6" title="Admin Actions" src="./github/6_d.webp" height="240"/>
</div>

<h3 id="technologies-front-br">🔨 Tecnologias</h3>

<p>As seguintes tecnologias foram utilizadas para construir o front-end de PlanetGame:</p>
<ul>
    <li><a href="https://www.npmjs.com/package/@mui/material">@mui/material</a></li>
    <li><a href="https://www.npmjs.com/package/@date-io/date-fns">@date-io/date-fns</a></li>
    <li><a href="https://www.npmjs.com/package/aos">aos</a></li>
    <li><a href="https://www.npmjs.com/package/axios">axios</a></li>
    <li><a href="https://www.npmjs.com/package/disable-scroll">disable-scroll</a></li>
    <li><a href="https://www.npmjs.com/package/hamburger-react">hamburger-react</a></li>
    <li><a href="https://www.npmjs.com/package/jwt-decode">jwt-decode</a></li>
    <li><a href="https://www.npmjs.com/package/react-helmet">react-helmet</a></li>
    <li><a href="https://www.npmjs.com/package/react-router-dom">react-router-dom</a></li>
</ul>

<h2 id="back-end-br">Back-end</h2>

<p>
     O back-end do PlanetGame foi desenvolvido com <b>PHP vanilla</b>, comportando-se como uma API: esperando requisições e retornando respostas JSON.
</p>

<p>
     O sistema de roteamento deste aplicativo foi desenvolvido do zero, fornecendo uma comunicação confiável e consistente entre front-end e back-end.
     Outro recurso interessante que foi desenvolvido do zero foi o <b>sistema de registro de erros</b> em conjunto com classes de exceção customizadas.
</p>

<p>
     Por fim, um ponto crucial a ser notado é que o back-end foi consistentemente apoiado por <b>testes unitários</b>, para manter uma qualidade de código sólida durante todo o processo de desenvolvimento.
</p>

<h3 id="technologies-back-br">🔨 Tecnologias</h3>

<p>As seguintes tecnologias foram utilizadas para desenvolver o sevidor do PlanetGame:</p>
<ul>
    <li><a href="https://packagist.org/packages/mpratt/relativetime">mpratt/relativetime</a> - Para converter datas em frases como "1 year ago"</li>
    <li><a href="https://packagist.org/packages/mofodojodino/profanity-filter">mofodojodino/profanity-filter</a> - Para eliminar palavrões enviados pelos formulários</li>
    <li><a href="https://packagist.org/packages/phpunit/phpunit">phpunit/phpunit</a></li>
</ul>

<h2 id="database-br">Banco de Dados</h2>

<p>O banco de dados escolhido para o PlanetGame foi o <a href="https://www.mysql.com">MySql</a>. Confira uma breve descrição das tabelas:</p>
<ul>
     <li><strong>tb_admin:</strong> armazena administradores;</li>
     <li><strong>tb_game:</strong> armazena jogos. Está relacionado a gêneros e publicadoras;</li>
     <li><strong>tb_genre:</strong> armazena gêneros de jogos;</li>
     <li><strong>tb_publisher:</strong> armazena as publicadoras de jogos</li>
</ul>

<h3 id="how-to-set-up-br">❓ Como configurar</h3>

<p>Se você deseja fazer um <em>fork</em> deste projeto, você precisará configurar as seguintes variáveis de ambiente:</p>

<h4 id="client-side-">Client-side:</h4>

<ul>
     <li>
         <strong>REACT_APP_API_URL</strong> (URL do servidor para enviar solicitações)
     </li>
     <li>
         <strong>REACT_APP_PUBLIC_URL</strong> (URL de front-end)
     </li>
</ul>

<p><h4 id="server-side-">Server-side:</h4></p>
<ul>
     <li>
         <strong>ADMIN_SALT</strong> (Salt para gerenciar as senhas do administrador)
     </li>
     <li>
         <strong>CLEARDB_DATABASE_URL</strong> (URL para conexão de banco de dados)
     </li>
     <li>
         <strong>CLIENT_URL</strong> (URL de front-end para fins de CORS)
     </li>
     <li>
         <strong>JWT_TOKEN_KEY</strong> (segredo JWT para gerenciamento de tokens)
     </li>
</ul>

<h3 id="inspirations-br">😁 Inspirações</h3>

<p>PlanetGame foi inspirado pelos seguintes projetos do Figma:</p>
<ul>
    <li><a href="https://www.figma.com/community/file/871334426251486294">GWP TeamFight Tactics</a> (inspirou a paleta de cores usada no PlanetGame)</li>
    <li><a href="https://www.figma.com/community/file/814078062940878144">LOL - 404 page for Weekly Warm-Up</a> (inspirou a UI do PlanetGame)</li>
</ul>


<h3 id="autor-br">👩‍🦲 Autor</h3>

<p>Full stack  desenvolvido por <strong>Matheus do Livramento</strong>.</p>
<p><a href="https://github.com/livramatheus">GitHub</a> | <a href="https://www.linkedin.com/in/livramatheus">LinkedIn</a> | <a href="https://www.livramento.dev/">Website</a></p>
