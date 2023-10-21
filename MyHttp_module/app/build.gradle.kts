/*
 * This file was generated by the Gradle 'init' task.
 *
 * This generated file contains a sample Java application project to get you started.
 * For more details on building Java & JVM projects, please refer to https://docs.gradle.org/8.3/userguide/building_java_projects.html in the Gradle documentation.
 * This project uses @Incubating APIs which are subject to change.
 */

plugins {
    // Apply the application plugin to add support for building a CLI application in Java.
    application
}

repositories {
    // Use Maven Central for resolving dependencies.
    maven {
        url = uri("https://oss.sonatype.org/content/repositories/snapshots")
    }
    mavenLocal()
    mavenCentral()
}

dependencies {
    // This dependency is used by the application.
    implementation("pro.gravit.launcher:launcher-core:5.5.0-SNAPSHOT")
    implementation("pro.gravit.launcher:launcher-ws-api:5.5.0-SNAPSHOT")
    implementation("pro.gravit.launcher:launchserver-api:5.5.0-SNAPSHOT")
    implementation("com.google.code.gson:gson:2.8.9")
    implementation("org.apache.logging.log4j:log4j-api:2.20.0")
}

testing {
    suites {
        // Configure the built-in test suite
        val test by getting(JvmTestSuite::class) {
            // Use JUnit Jupiter test framework
            useJUnitJupiter("5.9.3")
        }
    }
}

// Apply a specific Java toolchain to ease working on different environments.
java {
    toolchain {
        languageVersion.set(JavaLanguageVersion.of(21))
    }
}

tasks.jar {
    manifest.attributes["Module-Main-Class"] = "pro.gravit.launchermodules.myhttp.MyHttpModule"
}