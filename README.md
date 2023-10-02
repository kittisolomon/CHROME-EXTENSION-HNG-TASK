

## HNG X Stage Five Task

The project is a backend infrastructure that powers a chrome extension, that allows users screen record their screens and save to a cloud storage, for the purspose of this project Amazon s3 bucket was used for the storage of screen recording, the API also has the ability to transcribe text from recorded videos, this was made possible by integrating Openai's whisper API into the project.



## Stack Used
- PHP
- Laravel
- Mysql
- Composer

## External Tools used
- Whisper API
- Amazon s3 bucket



## Project Setup  Guide:

- Fork this REPO
- Clone the repo from your GitHub account to your local PC
```
https://github.com/kittisolomon/SCREEN-RECORD-UPLOAD-API-HNG-TASK.git
```

- Navigate to the cloned Project directory
```
 cd cloned-project-directory
```

- Run the composer command to install depencies

```
composer install
```

- Create '.env' file in the project root directory
- Edit the file, copy the Database settings below and paste in your '.env' file
```
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=My_Database
  DB_USERNAME=root
  DB_PASSWORD=
```
- Generate an Application Key with the command:
```vbnet
php artisan key:generate
```
- Create a database:

 Create a Database in your local database management system (e.g MYSQL) with the same name as defined in your '.env' file above

- Run Migrations:
  To create the project database table, run the command below

  ```
  php artisan migrate
  ```
- Start the Development Server

```
php artisan serve
```
by default your app should be running on 'http://localhost:8000'

Hurray!!! :rocket: you have set the project up and running! :smile:

## API DOCUMENTATION

### 
>Live hosting Link:
```
https://hng-task-two.afundcap.com/public/api
```

## POST /api/save_video
- Accepts a video from the Frontend. The video must be in MP4 format.
Request:
```
{
  "file": "video"
}
```
Response:
```
 {
    "message": "Video Uploaded Successfully",
    "status_code": 201,
    "data": {
        "id": "1",
        "file_name": "short_poem.mp4",
        "title": "short_poem",
        "file_size": "0.76mb",
        "file_length": "0:49",
        "url": "https://videohng.s3.amazonaws.com/videos/short_poem.mp4",
        "transcription": "Life can bring you joy, life can bring you sadness, life can bring you hate, life can bring you madness. Life can catch you off guard, can hit you fast and it can hit you hard. It knocks you down to your lowest low until your vulnerabilities begin to show. There are those little moments that you see. Life ends so bad for you or for me. Love and hope, emotions that are fulfilling. So when you think about it, life is worth living.",
        "slug": "short-poem",
        "uploaded_at": "2023-10-01 19:33:00"
    }
}

```


## GET /api

Retrieves all saved recordings in the Database.

Response:

```vbnet
{
    "data": [
        {
            "id": "1",
            "file_name": "short_poem.mp4",
            "title": "short_poem",
            "file_size": "0.76mb",
            "file_length": "0:49",
            "url": "https://videohng.s3.amazonaws.com/videos/short_poem.mp4",
            "transcription": " Life can bring you joy, life can bring you sadness, life can bring you hate, life can bring you madness. Life can catch you off guard, can hit you fast and it can hit you hard. It knocks you down to your lowest low until your vulnerabilities begin to show. There are those little moments that you see. Life ends so bad for you or for me. Love and hope, emotions that are fulfilling. So when you think about it, life is worth living.",
            "slug": "short-poem",
            "uploaded_at": "2023-10-01 19:33:00"
        }
    ]
}
```

## GET /api/{id}

Retrieves a single Recording.

Parameter: id 
The id of the Recording saved in the database.
Response:

```vbnet
{
    "data": {
        "id": "1",
        "file_name": "short_poem.mp4",
        "title": "short_poem",
        "file_size": "0.76mb",
        "file_length": "0:49",
        "url": "https://videohng.s3.amazonaws.com/videos/short_poem.mp4",
        "transcription": " Life can bring you joy, life can bring you sadness, life can bring you hate, life can bring you madness. Life can catch you off guard, can hit you fast and it can hit you hard. It knocks you down to your lowest low until your vulnerabilities begin to show. There are those little moments that you see. Life ends so bad for you or for me. Love and hope, emotions that are fulfilling. So when you think about it, life is worth living.",
        "slug": "short-poem",
        "uploaded_at": "2023-10-01 19:33:00"
    }
}
```


## DELETE /api/{id}
Deletes recording from both the database and s3 bucket.

Parameter: id
The id of the video.
Response:

```vbnet
{
  'message' => "Recording Deleted Successfully",
  'status_code' => 200
}
```

