pipeline {
  agent: any;
  stages {
    stage('Code Quality') {
      steps {
        sh 'echo testing code quality'
  }
    }
    stage('Unit Tests') {
      steps {
        sh 'echo running unit tests'
      }
    }
    stage('Build') {
      steps {
        sh 'echo Building the app'
      }
    }
    stage('Delivery') {
      steps {
        sh 'echo Delivering to the server'
      }
    }
    stage('Deployment') {
      steps {
        sh 'echo Deploying the app'
      }
    }
  }
}
