pipeline {
    agent any

    environment {
        COMPOSE_PROJECT_NAME = 'chat-reverb-deployment'
        AWS_ACCESS_KEY_ID=credentials('AWS_ACCESS_KEY_ID')
        AWS_SECRET_ACCESS_KEY=credentials('AWS_SECRET_ACCESS_KEY')
        REVERB_APP_ID=credentials('REVERB_APP_ID')
        REVERB_APP_KEY=credentials('REVERB_APP_KEY')
        REVERB_APP_SECRET=credentials('REVERB_APP_SECRET')
    }

    stages {
        stage('Clean Previous') {
            steps {
                sh '''
                    # Remove containers
                    docker compose down
                    
                    # Remove project images
                    docker images -q ${COMPOSE_PROJECT_NAME}* | xargs -r docker rmi -f
                    
                    # Clean network
                    docker network prune -f
                '''
            }
        }

        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Build') {
            steps {
                sh 'docker compose build'
            }
        }

        stage('Deploy') {
            steps {
                withCredentials([
                string(credentialsId: 'AWS_ACCESS_KEY_ID', variable: 'AWS_ACCESS_KEY_ID'),
                string(credentialsId: 'AWS_SECRET_ACCESS_KEY', variable: 'AWS_SECRET_ACCESS_KEY'),
                string(credentialsId: 'REVERB_APP_ID', variable: 'REVERB_APP_ID'),
                string(credentialsId: 'REVERB_APP_KEY', variable: 'REVERB_APP_KEY'),
                string(credentialsId: 'REVERB_APP_SECRET', variable: 'REVERB_APP_SECRET')
                ]) 
                    {
                    sh """
                        docker compose up -d
                        
                        # Wait container ready
                        sleep 10
                        
                        # Run migration and setup Laravel
                        docker compose exec -T app cp .env.example .env
                        docker compose exec -T app php artisan key:generate

                        # Inject secrets
                        echo "AWS_ACCESS_KEY_ID=\$AWS_ACCESS_KEY_ID"
                        echo "REVERB_APP_ID=\$REVERB_APP_ID"
                        docker compose exec -T app sh -c 'sed -i "s|^AWS_ACCESS_KEY_ID=.*|AWS_ACCESS_KEY_ID=${AWS_ACCESS_KEY_ID}|" .env'
                        docker compose exec -T app sh -c 'sed -i "s|^AWS_SECRET_ACCESS_KEY=.*|AWS_SECRET_ACCESS_KEY=${AWS_SECRET_ACCESS_KEY}|" .env'
                        docker compose exec -T app sh -c 'sed -i "s|^REVERB_APP_ID=.*|REVERB_APP_ID=${REVERB_APP_ID}|" .env'
                        docker compose exec -T app sh -c 'sed -i "s|^REVERB_APP_KEY=.*|REVERB_APP_KEY=${REVERB_APP_KEY}|" .env'
                        docker compose exec -T app sh -c 'sed -i "s|^REVERB_APP_SECRET=.*|REVERB_APP_SECRET=${REVERB_APP_SECRET}|" .env'

                        docker compose exec -T app php artisan webpush:vapid
                        
                        docker compose exec -T app php artisan migrate --force
                        
                        docker compose exec -T app php artisan optimize:clear
                        docker compose exec -T app php artisan optimize

                        docker compose exec -T app npm run build

                        docker compose exec -T app php artisan reverb:start --debug &

                    """
                }
            }
        }
    }

    post {
        always {
            sh 'docker compose ps'
            cleanWs()
        }
    }
}