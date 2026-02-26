<?php

namespace App\DataFixtures;

use App\Entity\ProProceso;
use App\Entity\TipTipoDoc;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // Create admin user
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'admin123')
        );
        $manager->persist($admin);

        // Create 5 ProProceso records
        $procesos = [
            ['nombre' => 'Ingeniería', 'prefijo' => 'ING'],
            ['nombre' => 'Recursos Humanos', 'prefijo' => 'RH'],
            ['nombre' => 'Finanzas', 'prefijo' => 'FIN'],
            ['nombre' => 'Operaciones', 'prefijo' => 'OPE'],
            ['nombre' => 'Calidad', 'prefijo' => 'CAL'],
        ];

        foreach ($procesos as $procesoData) {
            $proceso = new ProProceso();
            $proceso->setNombre($procesoData['nombre']);
            $proceso->setPrefijo($procesoData['prefijo']);
            $manager->persist($proceso);
        }

        // Create 5 TipTipoDoc records
        $tipos = [
            ['nombre' => 'Instructivo', 'prefijo' => 'INS'],
            ['nombre' => 'Procedimiento', 'prefijo' => 'PRO'],
            ['nombre' => 'Manual', 'prefijo' => 'MAN'],
            ['nombre' => 'Formato', 'prefijo' => 'FOR'],
            ['nombre' => 'Registro', 'prefijo' => 'REG'],
        ];

        foreach ($tipos as $tipoData) {
            $tipo = new TipTipoDoc();
            $tipo->setNombre($tipoData['nombre']);
            $tipo->setPrefijo($tipoData['prefijo']);
            $manager->persist($tipo);
        }

        $manager->flush();
    }
}


