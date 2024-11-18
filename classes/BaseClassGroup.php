<?php
abstract class BaseClassGroup{
    abstract protected function create(...$args);
    abstract protected function get_from_id(int $id);
    abstract protected function get_all();
    abstract protected function update(...$args);
    abstract protected function delete();
}