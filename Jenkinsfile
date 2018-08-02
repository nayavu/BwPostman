pipeline {
  agent any
  parameters {
    string(name: "VERSION_NUMBER", defaultValue: "2.1.0", description: "The new/next version number of the project.")
    string(name: "VAGRANT_DIR", defaultValue: "/vms-uni2/vagrant/infrastructure/farm1/J-Tester", description: "Path to the vagrant file")
  }
  stages {
    stage('Build') {
      steps {
//        sh "ansible-playbook ${WORKSPACE}/build/playbooks/build_package.yml --extra-vars 'project_base_dir=${WORKSPACE} version_number=${params.VERSION_NUMBER} build=${BUILD_NUMBER} mb4_support=true'"
        echo 'Unit-Tests'
        echo 'Smoke-Tests'
//        dir ('build/playbooks/') {
//          sh "sudo -u romana ansible-playbook acceptance-tester.yml -v --extra-vars 'project_base_dir=/data/repositories/BwPostman/ version_number=${params.VERSION_NUMBER} build=${BUILD_NUMBER} test_suite=smoke'"
//        }
//        sshagent(credentials: ['romana']) {
        sh "ssh -v -o StrictHostKeyChecking=no jenkins @192.168.121.172 cd /data/do-tests.sh"
//        }
        echo 'Akzeptanz-Tests passend zu Aenderungen'
        echo 'Validitaet von HTML'
        echo 'Code-Analyse: Testabdeckung'
        echo 'Code-Analyse: DRY'
        echo 'Code-Analyse: Komplexitaet'
        echo 'Code-Analyse: Warnungen'
        echo 'DB Rebase'
      }
    }
    stage('Acceptance Tests') {
      steps {
        echo 'Alle Akzeptanztests'
//        sh "ansible-playbook ${WORKSPACE}/build/playbooks/acceptance-tester.yml --extra-vars 'project_base_dir=${WORKSPACE} version_number=${params.VERSION_NUMBER} build=${BUILD_NUMBER} test_suite=accept1'"
      }
    }
    stage('Manual Tests') {
      steps {
        echo 'Benutzeroberflaeche'
        echo 'Worst-Case-Tests'
        echo 'nicht-funktionale Tests (Datenschutz, Sicherheit, ...)'
      }
    }
    stage('Release') {
      steps {
        echo 'Datum im Manifest aktualisieren'
        echo 'Upload auf Webserver'
        echo 'bei alter Webseite: Neues Paket und neues Objekt anlegen'
        echo 'Beschreibung auf Webseite aktualisieren'
        echo 'Handbuch im Web aktualisieren'
        echo 'Update-Server aktualisieren'
        echo 'JED aktualisieren'
      }
    }
  }
}
