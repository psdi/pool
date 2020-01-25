<?php

namespace Pool;

/**
 * src: https://www.php-fig.org/psr/psr-4/examples/
 */
class Autoloader
{
    /**
     * An associative array with elements in the following format:
     *   "namespace prefix" => [ array of base dirs ]
     * 
     * @var array
     */
    protected $prefixes = [];

    /** 
     * Absolute paths to check in (e.g. for framework and project paths)
     * 
     * @var array
     * */
    protected $absolutePaths = [];

    /**
     * Relevant for addNamespaceGroup
     * 
     * @var string
     */
    protected $groupNamespacePrefix = '';

    /**
     * Relevant for addNamespaceGroup
     * 
     * @var string
     */
    protected $groupBaseDirPrefix = '';

    public function __construct(...$absolutePaths)
    {
        $this->absolutePaths = $absolutePaths;
    }

    /**
     * For testing purposes only
     */
    public function get()
    {
        return [
            'prefixes' => $this->prefixes,
            'absolutes' => $this->absolutePaths,
        ];
    }

    public function register()
    {
        spl_autoload_register([$this, 'loadClass']);
    }

    /**
     * Adds a base directory for a namespace prefix.
     * 
     * @param string $prefix The namespace prefix
     * @param string $baseDir The base directory for the namespace
     * @param string $prepend If true, prepend $baseDir to $this->prefixes[$prefix] 
     * stack instead of appending it; this way, it is searched (and compared) first
     * @return void
     */
    public function addNamespace($prefix, $baseDir, $prepend = false) : void
    {
        $prefix = $this->groupNamespacePrefix . trim($prefix, '\\') . '\\';
        $baseDir = $this->groupBaseDirPrefix . rtrim($baseDir, '/') . '/';
        if (!isset($this->prefixes[$prefix])) {
            $this->prefixes[$prefix] = [];
        }
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $baseDir);
        } else {
            $this->prefixes[$prefix][] = $baseDir;
        }
    }

    /**
     * Accept an array of namespace/folder maps
     *
     * @param array $map
     */
    public function addNamespaceMap(array $map = []) : void
    {
        foreach ($map as $namespace => $dir) {
            $this->addNamespace($namespace, $dir);
        }
    }

    /**
     * Similar to addNamespaceMap(), just in nested form
     */
    public function addNamespaceGroup($prefix, $baseDir, $callable)
    {
        // Add topmost namespace
        $this->addNamespace($prefix, $baseDir);

        // Preserve previous prefixes
        $prevNamespacePrefix = $this->groupNamespacePrefix;
        $prevBaseDirPrefix = $this->groupBaseDirPrefix;

        // Append given prefixes to existing ones
        $this->groupNamespacePrefix .= rtrim($prefix, '\\') . '\\';
        $this->groupBaseDirPrefix .= rtrim($baseDir, '/') . '/';

        // Run callable
        $callable($this);

        // Revert prefixes
        $this->groupNamespacePrefix = $prevNamespacePrefix;
        $this->groupBaseDirPrefix = $prevBaseDirPrefix;
    }

    public function getPrefixes()
    {
        return $this->prefixes;
    }

    /**
     * Loads class file for a given class name
     */
    public function loadClass($class)
    {
        $prefix = $class;

        while (false !== $pos = strrpos($prefix, '\\')) {
            // retain trailing namespace separator in prefix
            $prefix = substr($prefix, 0, $pos + 1);
            // the rest is the relative class name
            $relativeClass = substr($class, $pos + 1);

            $mappedFile = $this->loadMappedFile($prefix, $relativeClass);
            if ($mappedFile) {
                return $mappedFile;
            }

            // remove trailing namespace separator for the next iteration
            $prefix = rtrim($prefix, '\\');
        }

        return false;
    }

    /**
     * Load mapped file
     *
     * @param string $prefix
     * @param string $relativeClass
     * @return string|bool
     */
    public function loadMappedFile($prefix, $relativeClass)
    {
        // check if prefix key exists
        if (!isset($this->prefixes[$prefix])) {
            return false;
        }

        // look through assigned base directories for this namespace prefix
        foreach ($this->prefixes[$prefix] as $baseDir) {
            foreach ($this->absolutePaths as $absPath) {
                $file = $absPath . $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

                if ($this->requireFile($file)) {
                    return $file;
                }
            }
        }

        return false;
    }

    /**
     * If a file exists, require it and return true.
     * 
     * @param string $file The requested file
     * @return bool True if file exists, otherwise false
     */
    public function requireFile($file)
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }
}