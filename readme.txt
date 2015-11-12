Como iniciar um projeto:

Escolha a pasta onde o projeto será instalado.
Execute o comando composer create-project --repository-url=http://packs.lab58.internit.br internit/site-base . ~1.2
Pra inserir no banco: php app/console doctrine:schema:update --force
Pra criar um admin: php app/console admin:create:user
Para criar raiz do menu: php app/console admin:menu:root

[ MÓDULOS ]
	[ RANDOM CHAT ]
		Para o módulo é necessário executar o comando: php app/console chat:create:turn
		.