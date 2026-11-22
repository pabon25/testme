
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

        // 1. Checkout source code from GitHub
        stage('Checkout') {
            steps {
                checkout scm
                echo 'Source code checked out successfully!'
            }
        }

        // 2. Run application tests
        stage('Test') {
            steps {
                echo 'Running application tests...'

                // Add actual tests here when available
                echo 'Tests completed!'
            }
        }

        // 3. Build Docker image locally
        stage('Build Docker Image') {
            steps {
                bat 'docker build -t %IMAGE_NAME%:%BUILD_NUMBER% .'
            }
        }

        // 4. Verify Docker image
        stage('Verify Docker Image') {
            steps {
                bat 'docker images %IMAGE_NAME%'
            }
        }

        // 5. Deploy PHP application to Spaceship cPanel
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
                        @echo off

                        echo =====================================
                        echo Preparing SSH authentication
                        echo =====================================

                        for /f "tokens=2 delims=," %%A in ('whoami /user /fo csv /nh') do set "JENKINS_SID=%%~A"

                        if not defined JENKINS_SID (
                            echo ERROR: Could not identify Jenkins account SID.
                            exit /b 1
                        )

                        echo Jenkins SID: %JENKINS_SID%

                        icacls "%SSH_KEY%" /inheritance:r /grant:r "*%JENKINS_SID%:R"

                        if errorlevel 1 (
                            echo ERROR: Failed to configure SSH key permissions.
                            exit /b 1
                        )

                        echo SSH key permissions configured successfully.

                        echo =====================================
                        echo Creating deployment package
                        echo =====================================

                        if exist deployment.zip del /f /q deployment.zip

                        tar -a -c -f deployment.zip --exclude=.git --exclude=.env --exclude=.env.* --exclude=deployment.zip --exclude=node_modules --exclude=.idea .

                        if errorlevel 1 (
                            echo ERROR: Failed to create deployment package.
                            exit /b 1
                        )

                        echo Deployment package created.

                        echo =====================================
                        echo Uploading files to Spaceship
                        echo =====================================

                        scp -i "%SSH_KEY%" -P %SSH_PORT% -o StrictHostKeyChecking=accept-new deployment.zip %SSH_USER%@%SERVER_IP%:%DEPLOY_DIR%/deployment.zip

                        if errorlevel 1 (
                            echo ERROR: File upload failed.
                            exit /b 1
                        )

                        echo Upload successful.

                        echo =====================================
                        echo Extracting application on Spaceship
                        echo =====================================

                        ssh -i "%SSH_KEY%" -p %SSH_PORT% -o StrictHostKeyChecking=accept-new %SSH_USER%@%SERVER_IP% "unzip -o %DEPLOY_DIR%/deployment.zip -d %DEPLOY_DIR% && rm -f %DEPLOY_DIR%/deployment.zip"

                        if errorlevel 1 (
                            echo ERROR: Remote extraction failed.
                            exit /b 1
                        )

                        echo =====================================
                        echo Deployment completed successfully
                        echo =====================================

                        del /f /q deployment.zip
                    '''
                }
            }
        }
    }

    post {

        success {
            echo 'Pipeline completed successfully!'
            echo 'Docker image built locally!'
            echo 'Application deployed to Spaceship!'
        }

        failure {
            echo 'Pipeline failed. Check the Console Output for details.'
        }

        always {
            echo 'Jenkins pipeline execution finished.'
        }
    }
}
