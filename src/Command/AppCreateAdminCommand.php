<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AppCreateAdminCommand
 * @package App\Command
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class AppCreateAdminCommand extends Command
{
    protected static $defaultName = 'app:create-admin';

    public function __construct(
        private UserPasswordEncoderInterface $encoder,
        private EntityManagerInterface $em
    ) {
        parent::__construct("app:create-admin");
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument("email", InputArgument::REQUIRED, "the user email")
            ->addArgument("password", InputArgument::REQUIRED, "the user password");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument("password");

        $user = User::fromCommand($email);
        $user->setPassword($this->encoder->encodePassword($user, $password));

        $this->em->persist($user);
        $this->em->flush();

        $io->success("User $email created with success");
        return Command::SUCCESS;
    }
}
