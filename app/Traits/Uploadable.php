<?php


trait Uploadable
{
    /**
     * Handle file upload and DB storage for a file:
     * - on CREATE
     *     - stores the file at the destination path
     *     - generates a name
     *     - stores the full path in the DB;
     * - on UPDATE
     *     - if the value is null, deletes the file and sets null in the DB
     *     - if the value is different, stores the different file and updates DB value.
     *
     * @param  [type] $value            Value for that column sent from the input.
     * @param  [type] $attribute_name   Model attribute name (and column in the db).
     * @param  [type] $disk             Filesystem disk used to store files.
     * @param  [type] $destination_path Path in disk where to store the files.
     */
    public function uploadFileToDisk($value, $attribute_name, $disk, $destination_path)
    {
        $request = \Request::instance();

        // if a new file is uploaded, delete the file from the disk
        if ($request->hasFile($attribute_name) &&
            $this->{$attribute_name} &&
            $this->{$attribute_name} != null) {
            \Storage::disk($disk)->delete($this->{$attribute_name});
            $this->attributes[$attribute_name] = null;
        }

        // if the file input is empty, delete the file from the disk
        if (is_null($value) && $this->{$attribute_name} != null) {
            \Storage::disk($disk)->delete($this->{$attribute_name});
            $this->attributes[$attribute_name] = null;
        }

        // if a new file is uploaded, store it on disk and its filename in the database
        if ($request->hasFile($attribute_name) && $request->file($attribute_name)->isValid()) {
            // 1. Generate a new file name
            $file = $request->file($attribute_name);
            $new_file_name = md5($file->getClientOriginalName().time()).'.'.$file->getClientOriginalExtension();

            // 2. Move the new file to the correct path
            $file_path = $file->storeAs($destination_path, $new_file_name, $disk);

            // 3. Save the complete path to the database
            $this->attributes[$attribute_name] = $file_path;
        }
    }


    /**
     * Handle multiple file upload and DB storage:
     * - if files are sent
     *     - stores the files at the destination path
     *     - generates random names
     *     - stores the full path in the DB, as JSON array;
     * - if a hidden input is sent to clear one or more files
     *     - deletes the file
     *     - removes that file from the DB.
     *
     * @param  [type] $value            Value for that column sent from the input.
     * @param  [type] $attribute_name   Model attribute name (and column in the db).
     * @param  [type] $disk             Filesystem disk used to store files.
     * @param  [type] $destination_path Path in disk where to store the files.
     */
    public function uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path)
    {
        $request = \Request::instance();
        $attribute_value = (array) $this->{$attribute_name};
        $files_to_clear = $request->get('clear_'.$attribute_name);

        // if a file has been marked for removal,
        // delete it from the disk and from the db
        if ($files_to_clear) {
            $attribute_value = (array) $this->{$attribute_name};
            foreach ($files_to_clear as $key => $filename) {
                \Storage::disk($disk)->delete($filename);
                $attribute_value = array_where($attribute_value, function ($value, $key) use ($filename) {
                    return $value != $filename;
                });
            }
        }

        // if a new file is uploaded, store it on disk and its filename in the database
        if ($request->hasFile($attribute_name)) {
            foreach ($request->file($attribute_name) as $file) {
                if ($file->isValid()) {
                    // 1. Generate a new file name
                    $new_file_name = md5($file->getClientOriginalName().time()).'.'.$file->getClientOriginalExtension();

                    // 2. Move the new file to the correct path
                    $file_path = $file->storeAs($destination_path, $new_file_name, $disk);

                    // 3. Add the public path to the database
                    $attribute_value[] = $file_path;
                }
            }
        }

        $this->attributes[$attribute_name] = json_encode($attribute_value);
    }

    /**
     * Handle uploading a single base64 image
     *
     * @param [type] $value
     * @param [type] $attribute_name
     * @param [type] $disk
     * @param [type] $destination_path
     * @param [type] $prefix
     * 
     * @return void
     */
    public function uploadBase64ImageToDisk($value, $attribute_name, $disk, $destination_path, $prefix = null)
    {
        // if the image was erased
        if ($value==null) {
            // delete the image from disk
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }

        // if a base64 was sent, store it in the db
        if (starts_with($value, 'data:image'))
        {

            \Storage::disk($disk)->delete($this->{$attribute_name});
            // 0. Make the image
            $image = \Image::make($value);
            // 1. Generate a filename.
            $filename = !is_null($prefix) ? $prefix . '_'. md5($value.time()).'.jpg' : md5($value.time()).'.jpg';
            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path.'/'.$filename, $image->stream());
            // 3. Save the path to the database
            $this->attributes[$attribute_name] = $destination_path.'/'.$filename;
        }
    }


}