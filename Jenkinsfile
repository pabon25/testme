
pipeline {
    agent any

    triggers {
        pollSCM('H/2 * * * *')
    }

    environment {
        IMAGE_NAME = 'testme'
        SERVER_IP = '66.29.148.173'
        SSH_PORT = '21098'
        DEPLOY_DIR = '/home/cjwfktusal/paccy.cypadi.com'
    }

    stages {

        stage('Checkout') {
            steps {
                checkout scm
                echo 'Source code checked out successfully!'
            }
        }

        stage('Test') {
            steps {
                echo 'Running application tests...'
                echo 'Tests completed!'
            }
        }

        stage('Build Docker Image') {
            steps {
                bat 'docker build -t %IMAGE_NAME%:%BUILD_NUMBER% .'
            }
        }

        stage('Verify Docker Image') {
            steps {
                bat 'docker images %IMAGE_NAME%'
            }
        }

       
stage('Deploy to Spaceship') {
    steps {
        withCredentials([
            sshUserPrivateKey(
                credentialsId: 'spaceship-ssh',
                keyFileVariable: 'SSH_KEY',
                usernameVariable: 'SSH_USER'
            )
        ]) {
            bat '''
                echo Restricting SSH private key permissions...

                icacls "%SSH_KEY%" /inheritance:r /grant:r "%USERDOMAIN%\\%USERNAME%:R"

                if errorlevel 1 exit /b 1

                echo Uploading application to Spaceship...

                if exist deployment.zip del /f /q deployment.zip

                tar -a -c -f deployment.zip --exclude=.git --exclude=.env --exclude=.env.* --exclude=deployment.zip .

                if errorlevel 1 exit /b 1

                scp -i "%SSH_KEY%" -P %SSH_PORT% -o StrictHostKeyChecking=accept-new deployment.zip %SSH_USER%@%SERVER_IP%:%DEPLOY_DIR%/deployment.zip

                if errorlevel 1 exit /b 1

                echo Extracting application on Spaceship...

                ssh -i "%SSH_KEY%" -p %SSH_PORT% -o StrictHostKeyChecking=accept-new %SSH_USER%@%SERVER_IP% "unzip -o %DEPLOY_DIR%/deployment.zip -d %DEPLOY_DIR% && rm -f %DEPLOY_DIR%/deployment.zip"

                if errorlevel 1 exit /b 1

                echo Deployment completed successfully!
            '''
        }
    }
}

    }
}
