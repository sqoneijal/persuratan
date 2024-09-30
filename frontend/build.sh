#!/bin/bash

# Generate SHA1 hash of the file content using openssl
hash_js=$(openssl sha1 < ./bundle/App.js | awk '{print $2}')
hash_css=$(openssl sha1 < ./bundle/App.css | awk '{print $2}')

# Copy the file
cp ./bundle/App.js ./bundle/app.$hash_js.js
cp ./bundle/App.css ./bundle/app.$hash_css.css

rm ./bundle/App.js
rm ./bundle/App.css

# SSH into the remote server and execute the commands
ssh debian@192.168.176.232 << 'EOF'
cd persuratan/mael-frontend/
sudo rm -rf bundle
exit
EOF

scp -r bundle debian@192.168.176.232:/home/debian/persuratan/mael-frontend

cp index-sample.html index.html

# Update the index.html file with the new hashed file names
file_path="index.html"
temp_file=$(mktemp)

# Replace the old CSS and JS file references with the new ones
sed "s|<link rel=\"stylesheet\" href=\"/bundle/App.css\" />|<link rel=\"stylesheet\" href=\"/bundle/app.$hash_css.css\" />|" "$file_path" > "$temp_file"
sed "s|<script src=\"/bundle/App.js\" type=\"module\"></script>|<script src=\"/bundle/app.$hash_js.js\" type=\"module\"></script>|" "$temp_file" > "$file_path"

# Remove the temporary file
rm "$temp_file"

scp -r index.html debian@192.168.176.232:/home/debian/persuratan/mael-frontend