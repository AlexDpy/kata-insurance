serve:
	docker run -it --rm -u `stat -c %u /home/alex/www/lab/insurance`:`stat -c %g /home/alex/www/lab/insurance` -v /home/alex/www/lab/insurance:/app -p 8080:8080 alexdpy/php:7.0 php -S 0.0.0.0:8080 -t public

logs:
	tail -f log/*.log
